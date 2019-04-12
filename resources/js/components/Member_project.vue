<template>
  <div class="container">
    <div class="row justify-content-left">
      <div class="col-md-4 mt-3">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">My Project</h3>
            <div class="card-tools">
              <button class="btn btn-success" @click="newModal">
                Add New
                <i class="fas fa-user-plus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div v-for="projects in projects" :key="projects.id">
            <div class="card-body">
              <strong>
                <i class="fa fa-book mr-1"></i> Project Assigned
              </strong>

              <p class="text-muted">{{projects.project_title}}</p>

              <hr>

              <strong>
                <i class="fa fa-map-marker mr-1"></i> Project Goal
              </strong>

              <p class="text-muted">{{projects.project_description}}</p>

              <hr>

              <strong>
                <i class="fa fa-fill mr-1"></i> My Team
              </strong>

              <p class="text-muted">{{projects.team}}</p>
              <hr>
              <strong>
                <i class="fa fa-meh-rolling-eyes mr-1"></i> Role Assigned
              </strong>

              <p class="text-muted">{{projects.role}}</p>
            </div>
            <!-- /.card-body -->
          </div>
        </div>

        <div
          class="modal fade"
          id="addNew"
          tabindex="-1"
          role="dialog"
          aria-labelledby="addNewLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-dialog-centered-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Create Task</h5>
                <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editmode ? updateTask() : createTask()">
                <div class="modal-body">
                  <div v-if="!editmode" class="form-group">
                    <input
                      v-model="form.task_name"
                      type="text"
                      name="task_name"
                      placeholder="Enter Task Name"
                      class="form-control"
                      :class="{'is-invalid':form.errors.has('task_name')}"
                    >
                    <has-error :form="form" field="task_name"></has-error>
                  </div>
                  <div v-if="editmode" class="form-group">
                    <dt>Task Name</dt>
                    <dl>{{this.form.task_name| upText}}</dl>
                  </div>
                  <div v-if="!editmode" class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="fas fa-calendar"></i>
                        </span>
                      </div>
                      <input
                        v-model="form.end_date"
                        type="date"
                        class="form-control float-right"
                        name="end_date"
                        placeholder="Enter task end date"
                      >
                    </div>
                    <has-error :form="form" field="end_date"></has-error>
                  </div>
                  <div v-if="editmode" class="form-group">
                    <dt>End Date</dt>
                    <dl>{{this.form.end_date}}</dl>
                  </div>
                  <dt>Priority</dt>
                  <div class="form-group">
                    <select
                      name="priority"
                      v-model="form.priority"
                      id="priority"
                      class="form-control"
                      :class="{'is-invalid':form.errors.has('priority')}"
                    >
                      <option disabled value>Select Priority</option>
                      <option value="Low">Low</option>
                      <option value="Medium">Medium</option>
                      <option value="High">High</option>
                    </select>
                    <has-error :form="form" field="priority"></has-error>
                  </div>
                  <dt>Status</dt>
                  <div class="form-group">
                    <select
                      name="status"
                      v-model="form.status"
                      id="status"
                      class="form-control"
                      :class="{'is-invalid':form.errors.has('status')}"
                    >
                      <option disabled value>Select Status</option>
                      <option value="Received">Received</option>
                      <option value="Analysis">Analysis</option>
                      <option value="Requirement Gathering">Requirement Gathering</option>
                      <option value="In Progress">In Progress</option>
                      <option value="Completed">Completed</option>
                    </select>
                    <has-error :form="form" field="status"></has-error>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                  <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8 mt-3">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Tasks</h3>
          </div>

          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table table-condensed">
              <tbody>
                <tr>
                  <th>Task</th>
                  <th>Progress</th>
                  <th>Date</th>
                  <th>Priority</th>
                  <th>Status</th>
                  <th></th>
                </tr>
                <tr v-for="task in tasks" :key="task.id">
                  <td>{{task.task_name}}</td>
                  <td>
                    <b-progress
                      :value="task.percentage"
                      variant="info"
                      striped
                      :animated="animate"
                      class="mt-2"
                      show-progress
                    />
                  </td>
                  <td>{{task.end_date | dateFormat}}</td>
                  <td v-if="task.priority=='Low'">
                    <span class="badge badge-success">{{task.priority}}</span>
                  </td>
                  <td v-else-if="task.priority=='Medium'">
                    <span class="badge badge-warning">{{task.priority}}</span>
                  </td>
                  <td v-else>
                    <span class="badge badge-danger">{{task.priority}}</span>
                  </td>
                  <!-- <td>{{task.priority}}</td> -->
                  <td>{{task.status}}</td>
                  <td>
                    <a href="#" @click="editTask(task)">
                      <i class="fa fa-edit"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- /.card-body -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      animate: true,
      projects: [],
      editmode: false,
      tasks: [],
      form: new Form({
        id: "",
        task_name: "",
        end_date: "",
        priority: "",
        status: "",
        percentage: ""
      })
    };
  },
  methods: {
    loadMyProjects() {
      if (this.$gate.isMember()) {
        axios.get("api/m_projects").then(({ data }) => (this.projects = data));
      }
    },
    loadTasks() {
      axios.get("api/tasks").then(({ data }) => (this.tasks = data));
    },
    createTask() {
      this.$Progress.start();
      this.form.post("api/tasks");
      Fire.$emit("Successful");
      $("#addNew").modal("hide");

      toast.fire({
        type: "success",
        title: "Project Details created successfully"
      });
      this.$Progress.finish();
    },
    editTask(task) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(task);
    },
    updateTask(tasks) {
      this.$Progress.start();
      this.form
        .put("api/tasks/" + this.form.id)
        .then(() => {
          $("#addNew").modal("hide");
          toast.fire({
            type: "success",
            title: "Task updated successfully"
          });
          this.$Progress.finish();
          Fire.$emit("Successful");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    }
  },

  mounted() {
    this.loadMyProjects();
    this.loadTasks();
    Fire.$on("Successful", () => {
      this.loadTasks();
    });
  }
};
</script>
