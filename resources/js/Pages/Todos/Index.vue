<template>
  <div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Todo List</h1>
      <a href="/todos/create" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded shadow hover:shadow-lg transform hover:-translate-y-0.5 transition">Add Todo</a>
    </div>

    <!-- Add Filters -->
    <div class="mb-4 flex gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Priority Filter</label>
        <select v-model="filters.priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          <option 
            v-for="priority in priorityFilterOptions" 
            :key="priority.value"
            :value="priority.value"
            :class="priority.class"
          >
            {{ priority.label }}
          </option>
        </select>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700">Status Filter</label>
        <select v-model="filters.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          <option 
            v-for="status in statusFilterOptions" 
            :key="status.value"
            :value="status.value"
            :class="status.class"
          >
            {{ status.label }}
          </option>
        </select>
      </div>
    </div>

    <table class="table-auto w-full border-collapse">
      <thead>
        <tr>
          <th class="border p-2">Title</th>
          <th class="border p-2">Description</th>
          <th class="border p-2">Priority</th>
          <th class="border p-2">Due Date</th>
          <th class="border p-2">Status</th>
          <th class="border p-2">Tags</th>
          <th class="border p-2">Comments</th>
          <th class="border p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="todo in filteredTodos" :key="todo.id">
          <td class="border p-2">{{ todo.title }}</td>
          <td class="border p-2">{{ todo.description }}</td>
          <td class="border p-2">
            <select 
              v-model="todo.priority"
              @change="updateTodo(todo.id, { priority: todo.priority })"
              class="p-1 rounded border w-full"
            >
              <option 
                v-for="priority in priorities" 
                :key="priority.value"
                :value="priority.value"
                :class="priority.class"
              >
                {{ priority.label }}
              </option>
            </select>
          </td>
          <td class="border p-2">
            <input 
              type="date" 
              :value="formatDateForInput(todo.due_date)"
              @change="updateTodo(todo.id, { due_date: $event.target.value })"
              class="p-1 rounded border"
            >
            <span v-if="isOverdue(todo.due_date,todo.status)" class="text-red-500 ml-2">(Overdue)</span>
          </td>
          <td class="border p-2">
            <select 
              v-model="todo.status"
              @change="updateTodo(todo.id, { status: todo.status })"
              class="p-1 rounded border w-full"
            >
              <option 
                v-for="status in statuses" 
                :key="status.value"
                :value="status.value"
                :class="status.class"
              >
                {{ status.label }}
              </option>
            </select>
          </td>
          <!-- Rest of the table cells remain the same -->
          <td class="border p-2">
            <span
              v-for="tag in todo.tags"
              :key="tag.id"
              class="bg-blue-200 text-blue-800 rounded px-2 py-1 mr-2"
            >
              {{ tag.name }}
            </span>
          </td>
          <td class="border p-2">
            <div class="space-y-2">
              <div v-for="comment in todo.comments" :key="comment.id" class="text-sm flex justify-between items-center">
                <p class="bg-gray-100 p-2 rounded flex-grow">{{ comment.content }}</p>
                <button 
                  @click="deleteComment(todo.id, comment.id)" 
                  class="ml-2 text-red-500 hover:text-red-700"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <form @submit.prevent="addComment(todo.id)" class="flex gap-2">
                <input 
                  v-model="newComments[todo.id]" 
                  type="text" 
                  placeholder="Add a comment"
                  class="border rounded px-2 py-1 flex-1"
                />
                <button 
                  type="submit"
                  class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
                >
                  Add
                </button>
              </form>
            </div>
          </td>
          <td class="border p-2">
            <a :href="`/todos/${todo.id}/edit`" class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">Edit</a>
            <form :action="`/todos/${todo.id}`" method="POST" class="inline">
              <input type="hidden" name="_method" value="DELETE" />
              <input type="hidden" name="_token" :value="csrfToken" />
              <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">Delete</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    todos: Array,
  },
  data() {
    return {
      newComments: {},
      filters: {
        priority: '',
        status: ''
      },
      priorities: [
        { value: 'high', label: 'High Priority', class: 'text-red-600 font-medium' },
        { value: 'medium', label: 'Medium Priority', class: 'text-yellow-600 font-medium' },
        { value: 'low', label: 'Low Priority', class: 'text-green-600 font-medium' },
      ],
      statuses: [
        { value: 'todo', label: 'Todo', class: 'text-blue-600 font-medium' },
        { value: 'in_progress', label: 'In Progress', class: 'text-yellow-600 font-medium' },
        { value: 'review', label: 'Review', class: 'text-gray-600 font-medium' },
        { value: 'done', label: 'Done', class: 'text-green-600 font-medium' },
      ]
    }
  },
  computed: {
    csrfToken() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    },
    filteredTodos() {
      return this.todos.filter(todo => {
        const priorityMatch = !this.filters.priority || todo.priority === this.filters.priority;
        const statusMatch = !this.filters.status || todo.status === this.filters.status;
        return priorityMatch && statusMatch;
      });
    },
    priorityFilterOptions() {
      return [
        { value: '', label: 'All Priorities', class: 'text-gray-700' },
        ...this.priorities
      ]
    },
    statusFilterOptions() {
      return [
        { value: '', label: 'All Statuses', class: 'text-gray-700' },
        ...this.statuses
      ]
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString();
    },
    formatDateForInput(date) {
      return date ? new Date(date).toISOString().split('T')[0] : '';
    },
    isOverdue(date,status) {
      if (status === 'done') return false;
      return new Date(date) < new Date();
    },
    formatStatus(status) {
      return status.split('_').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)
      ).join(' ');
    },
    async addComment(todoId) {
      try {
        const response = await this.$inertia.post(`/todos/${todoId}/comments`, {
          content: this.newComments[todoId],
        });
        this.newComments[todoId] = '';
      } catch (error) {
        console.error('Error adding comment:', error);
      }
    },
    async deleteComment(todoId, commentId) {
      if (confirm('Are you sure you want to delete this comment?')) {
        try {
          await this.$inertia.delete(`/todos/${todoId}/comments/${commentId}`);
        } catch (error) {
          console.error('Error deleting comment:', error);
        }
      }
    },
    async updateTodo(todoId, updates) {
      try {
        await this.$inertia.patch(`/todos/${todoId}`, updates);
      } catch (error) {
        console.error('Error updating todo:', error);
      }
    }
  }
};
</script>

<style>
select, input[type="date"] {
  appearance: none;
  background-color: white;
  border: 1px solid #e2e8f0;
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
  line-height: 1.25rem;
  border-radius: 0.25rem;
}

select {
  padding-right: 2rem;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
}

select option {
  padding: 0.5rem;
  margin: 0.25rem 0;
}

select option:checked {
  background-color: #f3f4f6;
}
</style>