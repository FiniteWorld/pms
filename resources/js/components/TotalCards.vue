<template>
  <div class="row mt-3">
    <div class="col-md-4">
      <!-- small box -->
      <div class="small-box bg-info" style="width:300px" v-if="$gate.isAdmin()">
        <div class="inner">
          <h3>{{this.dataCount[0]}}</h3>

          <p>Total Users</p>
        </div>
        <div class="icon">
          <i class="fas fa-chalkboard-teacher"></i>
        </div>
      </div>
      <div
        class="small-box bg-danger"
        style="width:300px; margin-left:65px "
        v-if="$gate.isMember()"
      >
        <div class="inner">
          <h3>{{this.dataCount[2]}}</h3>

          <p>My Project</p>
        </div>
        <div class="icon">
          <i class="fab fa-codepen"></i>
        </div>
        <a href="/myprojects" class="small-box-footer">
          More info
          <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
      <div class="col-md-4" v-if="$gate.isManager()">
        <!-- small box -->
        <div class="small-box bg-success" style="width:300px">
          <div class="inner">
            <h3>{{this.dataCount[3]}}</h3>

            <p>My Members</p>
          </div>
          <div class="icon">
            <i class="fas fa-sitemap"></i>
          </div>
          <router-link to="/team" class="small-box-footer">
            More info
            <i class="fas fa-arrow-circle-right"></i>
          </router-link>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- ./col -->
    <div class="col-md-4">
      <!-- small box -->
      <div class="col-md-4" v-if="$gate.isAdmin()">
        <!-- small box -->
        <div class="small-box bg-success" style="width:300px; margin-left:30px">
          <div class="inner">
            <h3>{{this.dataCount[1]}}</h3>

            <p>Total Teams</p>
          </div>
          <div class="icon">
            <i class="fas fa-sitemap"></i>
          </div>
        </div>
      </div>
      <div
        class="small-box bg-danger"
        style="width:300px; margin-left:65px "
        v-if="$gate.isManager()"
      >
        <div class="inner">
          <h3>{{this.dataCount[5]}}</h3>

          <p>My Project/s</p>
        </div>
        <div class="icon">
          <i class="fab fa-codepen"></i>
        </div>
        <a href="/projects" class="small-box-footer">
          More info
          <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-md-4">
      <div
        class="small-box bg-danger"
        style="width:300px; margin-left:65px "
        v-if="$gate.isAdmin()"
      >
        <div class="inner">
          <h3>{{this.dataCount[4]}}</h3>

          <p>Total Project/s</p>
        </div>
        <div class="icon">
          <i class="fab fa-codepen"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dataCount: {}
    };
  },
  methods: {
    displayCount() {
      axios.get("api/counts").then(response => {
        this.dataCount = response.data;
      });
    }
  },

  created() {
    this.displayCount();
  }
};
</script>
