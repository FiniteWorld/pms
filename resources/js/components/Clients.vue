<template>
  <div class="container">
    <div class="row justify mt-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Clients</h3>

            <div class="card-tools" v-if="$gate.isManager()">
              <button class="btn btn-success" @click="newModal">
                Add New
                <i class="fas fa-user-plus"></i>
              </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Companies</th>

                    <th></th>
                  </tr>

                  <tr v-for="client in clients" :key="client.id">
                    <td>{{client.client_name | upText}}</td>
                    <td>{{ client.client_email}}</td>
                    <td>{{client.client_company| upText}}</td>

                    <td>
                      <a href="#">
                        <i class="fa fa-edit" @click="editClient(client)"></i>
                      </a>
                      /
                      <a href="#">
                        <i class="fa fa-trash" @click="deleteClient(client.id)"></i>
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
                  <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add New Client</h5>
                  <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update Client's Info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form @submit.prevent=" editmode ? updateClient() : createClient()">
                  <div class="modal-body">
                    <div class="form-group">
                      <input
                        v-model="form.client_name"
                        type="text"
                        name="client_name"
                        placeholder="Enter Client's Name"
                        class="form-control"
                        :class="{'is-invalid':form.errors.has('client_name')}"
                      >
                      <has-error :form="form" field="client_name"></has-error>
                    </div>
                    <div class="form-group">
                      <input
                        v-model="form.client_email"
                        type="email"
                        name="client_name"
                        placeholder="Enter Client's Email"
                        class="form-control"
                        :class="{'is-invalid':form.errors.has('client_email')}"
                      >
                      <has-error :form="form" field="client_email"></has-error>
                    </div>
                    <div class="form-group">
                      <input
                        v-model="form.client_company"
                        type="text"
                        name="client_company"
                        placeholder="Enter Client's Company"
                        class="form-control"
                        :class="{'is-invalid':form.errors.has('client_company')}"
                      >
                      <has-error :form="form" field="client_company"></has-error>
                    </div>
                    <div class="form-group">
                      <input
                        v-model="form.client_address"
                        type="text"
                        name="client_address"
                        placeholder="Enter Address"
                        class="form-control"
                        :class="{'is-invalid':form.errors.has('client_address')}"
                      >
                      <has-error :form="form" field="client_address"></has-error>
                    </div>
                    <div class="form-group">
                      <input
                        v-model="form.client_contact"
                        type="tel"
                        pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                        name="client_contact"
                        class="form-control"
                      >
                      <span class="note">Format: 999-999-9999</span>
                      <has-error :form="form" field="client_contact"></has-error>
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
      clients: {},
      form: new Form({
        id: "",
        user_id: "",
        client_name: "",
        client_email: "",
        client_address: "",
        client_contact: "",
        client_company: ""
      })
    };
  },
  methods: {
    getResults() {
      axios.get("api/clients?page=" + page).then(response => {
        this.clients = response.data;
      });
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    editClient(client) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(client);
    },
    deleteClient(id) {
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
              .delete("api/clients/" + id)
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
    loadClients() {
      if (this.$gate.isManager()) {
        axios.get("api/clients").then(({ data }) => (this.clients = data));
      }
    },
    updateClient() {
      this.$Progress.start();
      this.form
        .put("api/clients/" + this.form.id)
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
    createClient() {
      this.$Progress.start();
      this.form.post("api/clients");
      Fire.$emit("Successful");
      $("#addNew").modal("hide");
      swal.fire({
        title: "Client's detais added successfully",
        text: "Would you like to add project now?",
        icon: "success",
        buttons: ["Not now", "Yes"]
      });

      // toast.fire({
      //   type: "success",
      //   title: "Project Details created successfully"
      // });
      this.$Progress.finish();
    }
  },
  created() {
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findClient?q=" + query)
        .then(data => {
          this.clients = data.data;
        })
        .catch(() => {});
    });
    this.loadClients();
    Fire.$on("Successful", () => {
      this.loadClients();
    });
  }
};
</script>

