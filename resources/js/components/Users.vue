<template>
  <div class="container">
    <div class="row mt-5" v-if="$gate.isAdmin()">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">User's Table</h3>

            <div class="card-tools">
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
                    
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Registered</th>
                    <th>Modify</th>
                  </tr>

                  <tr v-for="user in users.data" :key="user.id">
                   
                    <td>{{user.name | upText}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.type | upText}}</td>
                    <td>{{user.created_at | dateFormat}}</td>
                    <td>
                      <a href="#" @click="editUser(user)">
                        <i class="fa fa-edit"></i>
                      </a>
                      /
                      <a href="#" @click="deleteUser(user.id)">
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="card-footer">
              <pagination :data="users" @pagination-change-page="getResults"></pagination>
            </div>
          </div>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- <div v-if="!$gate.isAdmin()">
      <not-found></not-found>
    </div>-->
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
            <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add New User</h5>
            <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update User's Info</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent=" editmode ? updateUser() : createUser()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Enter Name"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('name')}"
                >
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <select
                  name="type"
                  v-model="form.type"
                  id="type"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('type')}"
                >
                  <option disabled value>Select User Role</option>
                  <option value="Admin">Admin</option>
                  <option value="Manager">Manager</option>
                  <option value="Member">Member</option>
                </select>
                <has-error :form="form" field="type"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.email"
                  type="email"
                  name="email"
                  placeholder="Enter Email"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('email')}"
                >
                <has-error :form="form" field="email"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.bio"
                  type="bio"
                  id="bio"
                  name="bio"
                  placeholder="Enter Bio (Optional)"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('bio')}"
                >
                <has-error :form="form" field="bio"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.password"
                  id="password"
                  type="password"
                  name="password"
                  placeholder="Enter Password"
                  class="form-control"
                >
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
      users: {},
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        type: "",
        bio: "",
        photo: "",
        skills: "",
        education: "",
        location: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/user?page=" + page).then(response => {
        this.users = response.data;
      });
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    editUser(user) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(user);
    },
    deleteUser(id) {
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
              .delete("api/user/" + id)
              .then(() => {
                swal.fire(
                  "Deleted!",
                  "The selected user has been deleted.",
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
    loadUsers() {
      if (this.$gate.isAdminOrManager()) {
        axios.get("api/user").then(({ data }) => (this.users = data));
      }
    },
    updateUser() {
      this.$Progress.start();
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          $("#addNew").modal("hide");
          toast.fire({
            type: "success",
            title: "User updated successfully"
          });
          this.$Progress.finish();
          Fire.$emit("Successful");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    createUser() {
      this.$Progress.start();
      this.form.post("api/user");
      Fire.$emit("Successful");
      $("#addNew").modal("hide");

      toast.fire({
        type: "success",
        title: "User created successfully"
      });
      this.$Progress.finish();
    }
  },
  created() {
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findUser?q=" + query)
        .then(data => {
          this.users = data;
        })
        .catch(() => {});
    });
    this.loadUsers();
    Fire.$on("Successful", () => {
      this.loadUsers();
    });
    // setInterval(() => this.loadUsers(), 10000);
  }
};
</script>


