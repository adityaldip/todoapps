<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Add Todo</h1>
    
    <form @submit.prevent="submitForm" class="max-w-lg">
      <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
        <input 
          type="text" 
          v-model="form.title"
          id="title" 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
        />
      </div>
      <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea 
          v-model="form.description"
          id="description" 
          rows="4"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        ></textarea>
      </div>
      <div class="mb-4">
        <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
        <select 
          v-model="form.priority"
          id="priority"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
        >
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
        <input 
          type="date"
          v-model="form.due_date"
          id="due_date"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
        />
      </div>

      <div class="mb-4">
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select 
          v-model="form.status"
          id="status"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
        >
          <option value="todo">Todo</option>
          <option value="in_progress">In Progress</option>
          <option value="review">Review</option>
          <option value="done">Done</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
        <div class="flex gap-2 mb-2">
          <input 
            type="text" 
            v-model="newTag"
            placeholder="Add new tag"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          />
          <button 
            @click.prevent="addNewTag"
            class="inline-flex justify-center rounded-md border border-transparent bg-gray-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-700"
          >
            Add Tag
          </button>
        </div>
        <select 
          v-model="form.tags"
          id="tags" 
          multiple
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
          <option v-for="tag in tags" :key="tag.id" :value="tag.id">
            {{ tag.name }}
          </option>
        </select>
      </div>

      <button 
        type="submit"
        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
      >
        Save Todo
      </button>
    </form>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'
export default {
  props: {
    tags: Array,
  },
  
  setup(props) {
    const form = useForm({
      title: '',
      description: '',
      priority: 'low',
      due_date: '',
      status: 'todo',
      tags: [],
    })

    const newTag = ref('')
    const addNewTag = async () => {
      if (!newTag.value) return

      try {
        const response = await axios.post('/tags', {
          name: newTag.value
        })
        
        props.tags.push(response.data)
        newTag.value = ''
      } catch (error) {
        console.error('Error creating tag:', error)
      }
    }

    const submitForm = () => {
      form.post('/todos', {
        preserveScroll: true,
        onSuccess: () => {
          form.reset()
        },
      })
    }

    return { 
      form, 
      submitForm,
      newTag,
      addNewTag
    }
  }
}
</script>
