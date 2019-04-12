<template>
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Todo</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <form @submit.prevent="createTodo()">
        <div class="form-inline">
          <input
            v-model="form.task"
            type="text"
            name="task"
            placeholder="Enter Todo Event"
            class="form-control"
            :class="{'is-invalid':form.errors.has('task')}"
          >
          <has-error :form="form" field="task"></has-error>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus-square"></i>
          </button>
        </div>
      </form>
      <table class="table table-hover">
        <tr v-for="todo in todo" :key="todo.id">
          <td>{{todo.task}}</td>
          <td>
            <a href="#" @click="deleteTodo(todo.id)">
              <i class="fa fa-trash"></i>
            </a>
          </td>
        </tr>
      </table>
    </div>
  </div>
  <!-- /.card-body -->
</template>

<script>
export default {
  name: "Todo",
  data() {
    return {
      todo: {},
      form: new Form({
        id: "",
        user_id: "",
        task: ""
      })
    };
  },
  methods: {
    loadTodo() {
      axios.get("api/todo").then(({ data }) => (this.todo = data));
    },
    createTodo() {
      this.form.post("api/todo");
      Fire.$emit("Successful");

      toast.fire({
        type: "success",
        title: "Todo created successfully"
      });
    },
    deleteTodo(id) {
      this.form.delete("api/todo/" + id);
      Fire.$emit("Successful");

      toast.fire({
        type: "success",
        title: "Todo Removed successfully"
      });
    }
  },
  created() {
    this.loadTodo();
    Fire.$on("Successful", () => {
      this.loadTodo();
    });
  }
};
</script>
