<template>
  <div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Todo List</h1>
      <a href="/todos/create" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded shadow hover:shadow-lg transform hover:-translate-y-0.5 transition">Add Todo</a>
    </div>
    <table class="table-auto w-full border-collapse">
      <thead>
        <tr>
          <th class="border p-2">Title</th>
          <th class="border p-2">Description</th>
          <th class="border p-2">Tags</th>
          <th class="border p-2">Comments</th>
          <th class="border p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="todo in todos" :key="todo.id">
          <td class="border p-2">{{ todo.title }}</td>
          <td class="border p-2">{{ todo.description }}</td>
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
    }
  },
  computed: {
    csrfToken() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    },
  },
  methods: {
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
    }
  }
};
</script>
