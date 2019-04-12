<template>
  <div class="container">
    <div class="row justify-content">
      <div class="col-md-3 mt-3">
        <!--about me-->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">About Me</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="text-center">
              <img
                class="profile-user-img img-fluid img-circle"
                :src=" getProfilePhoto()"
                alt="User profile picture"
              >
            </div>
            <h3 class="profile-username text-center">{{this.form.name}}</h3>

            <p class="text-muted text-center">{{this.form.type}}</p>
            <strong>
              <i class="fa fa-book mr-1"></i> Education
            </strong>

            <p class="text-muted">{{this.form.education}}</p>

            <hr>

            <strong>
              <i class="fa fa-map-marker mr-1"></i> Location
            </strong>

            <p class="text-muted">{{this.form.location}}</p>

            <hr>

            <strong>
              <i class="fa fa-pencil mr-1"></i> Skills
            </strong>

            <p class="text-muted">{{this.form.skills}}</p>
          </div>
          <!-- /.card-body -->
        </div>
      </div>

      <!--right container -->
      <div class="col-md-9 mt-3">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link active" href="#settings" data-toggle="tab">Settings</a>
              </li>
            </ul>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <div class="active tab-pane" id="settings">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input
                        type="name"
                        v-model="form.name"
                        class="form-control"
                        id="inputName"
                        placeholder="Name"
                      >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input
                        type="email"
                        v-model="form.email"
                        class="form-control"
                        id="inputEmail"
                        placeholder="Email"
                      >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEducation" class="col-sm-2 control-label">Education</label>

                    <div class="col-sm-10">
                      <input
                        type="text"
                        v-model="form.education"
                        class="form-control"
                        id="inputEducation"
                        placeholder="Education"
                      >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputLocation" class="col-sm-2 control-label">Location</label>
                    <div class="col-sm-10">
                      <input
                        type="location"
                        class="form-control"
                        id="inputLocation"
                        v-model="form.location"
                        placeholder="Location"
                      >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Skills</label>
                    <div class="col-sm-10">
                      <input
                        type="skills"
                        class="form-control"
                        id="inputSkills"
                        v-model="form.skills"
                        placeholder="Skills"
                      >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
                    <div class="col-sm-10">
                      <input type="file" @change="updatePhoto" name="photo" class="form-input">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the
                          <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button
                        @click.prevent="updateInfo"
                        type="submit"
                        class="btn btn-primary"
                      >Update</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
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
        location: "",
        team: ""
      })
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    getProfilePhoto() {
      let photo =
        this.form.photo.length > 200
          ? this.form.photo
          : "images/profile/" + this.form.photo;
      return photo;
    },
    updateInfo() {
      this.$Progress.start();

      this.form
        .put("api/profile")
        .then(() => {
          toast.fire({
            type: "success",
            title: "Information updated successfully"
          });
          this.$Progress.finish();
          Fire.$emit("Successful");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },

    updatePhoto(e) {
      let file = e.target.files[0];
      let reader = new FileReader();

      reader.onloadend = file => {
        //console.log("RESULT", reader.result);
        this.form.photo = reader.result;
      };

      reader.readAsDataURL(file);
    }
  },
  created() {
    axios.get("api/profile").then(({ data }) => this.form.fill(data));
  }
};
</script>
