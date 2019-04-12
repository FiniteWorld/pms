<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 mt-3">
        <div class="card card-default card">
          <div class="card-header">
            <div class="card-title">
              <h3 class="card-title">Add Teams</h3>
            </div>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-widget="collapse">
                <i class="fa fa-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body table-responsive p-0">
            <button @click="newModal" class="btn btn-block btn-outline-primary">
              Add Team Member
              <i class="fas fa-user-plus"></i>
            </button>
            <table class="table table-hover">
              <tbody>
                <tr>
                  <th>ID</th>
                  <th>Role</th>
                  <th>Team Name</th>
                  <th>Member's ID</th>
                  <th>Project Assigned</th>
                  <th></th>
                </tr>

                <tr v-for="team in team.data" :key="team.id">
                  <td>{{team.id}}</td>
                  <td>{{team.role | upText}}</td>
                  <td>{{team.team_name | upText}}</td>
                  <td>{{team.name}}</td>
                  <td>{{team.project_title}}</td>
                  <td>
                    <a href="#" @click="editTeam(team)">
                      <i class="fa fa-edit"></i>
                    </a>
                    /
                    <a href="#" @click="deleteTeam(team.id)">
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
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
                  <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add</h5>
                  <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form @submit.prevent=" editmode ? updateTeam() : createTeam()">
                  <div class="modal-body">
                    <div class="form-group">
                      <input
                        v-model="form.role"
                        type="text"
                        name="role"
                        placeholder="Enter Role"
                        class="form-control"
                        :class="{'is-invalid':form.errors.has('role')}"
                      >
                      <has-error :form="form" field="role"></has-error>
                    </div>

                    <div class="form-group">
                      <select
                        name="member_id"
                        v-model="form.member_id"
                        id="member_id"
                        class="form-control"
                        :class="{'is-invalid':form.errors.has('member_id')}"
                      >
                        <option disabled value>Select Team Member</option>
                        <option
                          v-for="member in members"
                          :key="member.id"
                          v-bind:value="member.id"
                        >{{member.name}}</option>
                      </select>
                      <has-error :form="form" field="member_id"></has-error>
                    </div>
                    <div class="form-group">
                      <select
                        name="project_title"
                        v-model="form.project_title"
                        id="project_title"
                        class="form-control"
                        :class="{'is-invalid':form.errors.has('project_title')}"
                      >
                        <option disabled value>Select Project</option>
                        <option
                          v-for="project in projects"
                          :key="project.id"
                          v-bind:value="project.id"
                        >{{project.project_title}}</option>
                      </select>
                      <has-error :form="form" field="project_title"></has-error>
                    </div>
                    <div class="form-group">
                      <select
                        name="team_name"
                        v-model="form.team_name"
                        id="team_name"
                        class="form-control"
                        :class="{'is-invalid':form.errors.has('team_name')}"
                      >
                        <option disabled value>Select Team</option>
                        <option value="Team 1">Team 1</option>
                        <option value="Team 2">Team 2</option>
                        <option value="Team 3">Team 3</option>
                      </select>
                      <has-error :form="form" field="team_name"></has-error>
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
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editmode: false,
      members: {},
      team: {},
      projects: {},
      form: new Form({
        id: "",
        user_id: "",
        role: "",
        member_id: "",
        team_name: "",
        project_title: ""
      })
    };
  },
  methods: {
    loadMemberName() {
      axios.get("api/member").then(({ data }) => (this.members = data));
    },
    loadTeam() {
      axios.get("api/team").then(({ data }) => (this.team = data));
    },
    loadTitles() {
      axios.get("api/titles").then(({ data }) => (this.projects = data));
    },

    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },

    createTeam() {
      this.$Progress.start();
      this.form.post("api/team");
      Fire.$emit("Successful");
      $("#addNew").modal("hide");

      toast.fire({
        type: "success",
        title: "Team created successfully"
      });
      this.$Progress.finish();
    },

    updateTeam() {
      this.$Progress.start();
      this.form
        .put("api/team/" + this.form.id)
        .then(() => {
          $("#addNew").modal("hide");
          toast.fire({
            type: "success",
            title: "Team updated successfully"
          });
          this.$Progress.finish();
          Fire.$emit("Successful");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    editTeam(team) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(team);
    },
    deleteTeam(id) {
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
              .delete("api/team/" + id)
              .then(() => {
                swal.fire(
                  "Deleted!",
                  "The selected team member has been deleted.",
                  "success"
                );
                Fire.$emit("Successful");
              })
              .catch(() => {
                swal("Failed!!", "Something's wrong", "warning");
              });
          }
        });
    }
  },
  created() {
    this.loadMemberName();
    this.loadTitles();
    this.loadTeam();
    Fire.$on("Successful", () => {
      this.loadTeam();
    });
  }
};
</script>

