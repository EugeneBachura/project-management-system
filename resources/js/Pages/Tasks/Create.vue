<template>
  <div>
    <h1>Create Task</h1>

    <!-- Success -->
    <div v-if="$page.props.flash.success" class="text-green-500">
      {{ $page.props.flash.success }}
    </div>

    <!-- Form -->
    <form @submit.prevent="submitForm">
      <!-- Title -->
      <div>
        <input
          v-model="form.title"
          type="text"
          placeholder="Task Title"
          class="border p-2"
        />
        <div v-if="$page.props.errors.title" class="text-red-500">
          {{ $page.props.errors.title }}
        </div>
      </div>

      <!-- Description -->
      <div>
        <textarea
          v-model="form.description"
          placeholder="Task Description"
          class="border p-2"
        ></textarea>
        <div v-if="$page.props.errors.description" class="text-red-500">
          {{ $page.props.errors.description }}
        </div>
      </div>

      <!-- Status -->
      <div>
        <select v-model="form.status" class="border p-2">
          <option value="pending">Pending</option>
          <option value="in_progress">In Progress</option>
          <option value="completed">Completed</option>
        </select>
        <div v-if="$page.props.errors.status" class="text-red-500">
          {{ $page.props.errors.status }}
        </div>
      </div>

      <!-- Due Date -->
      <div>
        <input v-model="form.due_date" type="date" class="border p-2" />
        <div v-if="$page.props.errors.due_date" class="text-red-500">
          {{ $page.props.errors.due_date }}
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="bg-blue-500 text-white p-2">Create</button>
    </form>
  </div>
</template>

<script>
import { useForm, usePage } from "@inertiajs/inertia-vue3";

export default {
  setup() {
    const { props } = usePage();
    const form = useForm({
      title: props.old?.title || "",
      description: props.old?.description || "",
      status: props.old?.status || "pending",
      due_date: props.old?.due_date || "",
    });

    function submitForm() {
      form.post("/tasks", {
        onError: (errors) => {
          console.log("Validation errors:", errors);
        },
      });
    }

    return { form, submitForm };
  },
};
</script>
