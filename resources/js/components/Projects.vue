<template>
  <div class="container">
    <div class="row mt-5" v-if="$gate.isManager() || $gate.isAdmin()">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Projects</h3>

            <div class="card-tools" v-if="$gate.isManager()">
              <button class="btn btn-success" @click="newModal">
                Add New
                <i class="fas fa-user-plus"></i>
              </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th>Client</th>
                    <th>Project Title</th>
                    <th v-if="$gate.isAdmin()">Manager</th>
                    <th v-if="$gate.isManager()">Description</th>
                    <th>Assigned To</th>
                    <th></th>
                  </tr>

                  <tr v-for="project in project" :key="project.id">
                    <td>{{project.client_name | upText}}</td>
                    <td>{{project.project_title }}</td>
                    <td v-if="$gate.isAdmin()">{{project.name }}</td>
                    <td v-if="$gate.isManager()">{{project.project_description | upText}}</td>
                    <td>{{project.team | upText}}</td>
                    <td>
                      <a v-if="$gate.isManager()" href="#" @click="editProject(project)">
                        <i class="fa fa-edit"></i>
                      </a>
                      /
                      <a href="#" @click="deleteProject(project.id)">
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="card-footer">
              <!-- <pagination :data="project.data" @pagination-change-page="getResults"></pagination> -->
            </div>
          </div>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <div v-if="!$gate.isManager()">
      <not-found></not-found>
    </div>
    <div
      class="modal fade"
      id="addNew"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add New Project</h5>
            <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update Project's Info</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent=" editmode ? updateProject() : createProject()">
            <div class="modal-body">
              <div v-show="!editmode" class="form-group">
                <select
                  name="client_id"
                  v-model="form.client_id"
                  id="client_id"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('client_id')}"
                >
                  <option disabled value>Select Client Name</option>
                  <option
                    v-for="client in client"
                    :key="client.id"
                    v-bind:value="client.id"
                  >{{client.client_name}}</option>
                </select>
                <has-error :form="form" field="client_id"></has-error>
              </div>

              <div class="form-group">
                <input
                  v-model="form.project_title"
                  type="text"
                  name="project_title"
                  placeholder="Enter Title"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('project_title')}"
                >
                <has-error :form="form" field="project_title"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.project_description"
                  type="text"
                  name="project_description"
                  placeholder="Enter Description"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('project_description')}"
                >
                <has-error :form="form" field="project_description"></has-error>
              </div>
              <div class="form-group">
                <select
                  name="team"
                  v-model="form.team"
                  id="team"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('team')}"
                >
                  <option disabled value>Select Team</option>
                  <option value="Team 1">Team 1</option>
                  <option value="Team 2">Team 2</option>
                  <option value="Team 3">Team 3</option>
                </select>
                <has-error :form="form" field="team"></has-error>
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
</template>


<script>
export default {
  data() {
    return {
      editmode: false,
      project: {},
      client: {},
      form: new Form({
        id: "",
        user_id: "",
        client_id: "",
        project_title: "",
        project_description: "",
        team: ""
      })
    };
  },
  methods: {
    getResults() {
      axios.get("api/project?page=" + page).then(response => {
        this.project = response.data;
      });
    },
    loadClients() {
      axios.get("api/client_info").then(({ data }) => (this.client = data));
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    editProject(project) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(project);
    },
    deleteProject(id) {
      swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        })
        .then(result => {
          //request to server
          if (result.value) {
            this.form
              .delete("api/project/" + id)
              .then(() => {
                swal.fire(
                  "Deleted!",
                  "The selected project details have been deleted.",
                  "success"
                );
                Fire.$emit("Successful");
              })
              .catch(() => {
                swal("Failed!!", "Something's wrong", "warning");
              });
          }
        });
    },
    loadProjects() {
      if (this.$gate.isManager() || this.$gate.isAdmin()) {
        axios.get("api/project").then(({ data }) => (this.project = data));
      }
    },
    updateProject() {
      this.$Progress.start();
      this.form
        .put("api/project/" + this.form.id)
        .then(() => {
          $("#addNew").modal("hide");
          toast.fire({
            type: "success",
            title: "Project Details updated successfully"
          });

          this.$Progress.finish();
          Fire.$emit("Successful");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    createProject() {
      this.$Progress.start();
      this.form.post("api/project");
      Fire.$emit("Successful");
      $("#addNew").modal("hide");

      toast.fire({
        type: "success",
        title: "Project Details created successfully"
      });
      this.$Progress.finish();
    }
  },
  created() {
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findProject?q=" + query)
        .then(data => {
          this.projects = data;
        })
        .catch(() => {});
    });
    this.loadProjects();
    this.loadClients();
    Fire.$on("Successful", () => {
      this.loadProjects();
    });
  }
};
</script>
