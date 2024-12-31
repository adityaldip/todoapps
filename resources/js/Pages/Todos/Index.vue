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
  computed: {
    csrfToken() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    },
  },
};
</script>
