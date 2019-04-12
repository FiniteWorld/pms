<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row justify-content-center">
              <h1>
                <span class="badge badge-pill badge-info">Project Progress</span>
              </h1>
            </div>
            <div class="card-body">
              <div class="card card-info">
                <table class="table table-condensed">
                  <tbody>
                    <tr>
                      <th>Project</th>
                      <th>Company</th>

                      <th>Progress</th>
                    </tr>
                    <tr v-for="value in value" :key="value.proj_id">
                      <td>{{value.project_title}}</td>
                      <td>{{value.client_company}}</td>
                      <td>
                        <b-progress
                          :value="value.percentage"
                          variant="info"
                          striped
                          :animated="animate"
                          class="mt-2"
                          show-progress
                        />
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
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      max: 100,
      value: [],
      animate: true
    };
  },
  methods: {
    loadProjectStatus() {
      axios.get("api/statusManager").then(({ data }) => (this.value = data));
    }
  },
  mounted() {
    this.loadProjectStatus();
  }
};
</script>
