<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <TotalCards></TotalCards>
      </div>
      <div class="row justify-content-center" v-if="$gate.isMember()">
        <h1>
          <span class="badge badge-pill badge-secondary">My Progress</span>
        </h1>
      </div>
      <div v-if="$gate.isMember()">
        <b-progress
          :value="this.value[0]"
          height="35px"
          variant="success"
          striped
          :animated="animate"
          class="mt-2"
          show-progress
        />
      </div>
      <div class="row" v-if="$gate.isManager()">
        <ProgressManager></ProgressManager>
      </div>

      <div class="row">
        <div class="col-md-4 mt-3">
          <Todo></Todo>
        </div>
        <div class="col-md-7 mt-3">
          <Events></Events>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import Todo from "./Todo";
import Events from "./Events";
import TotalCards from "./TotalCards";
import ProgressManager from "./ProgressManager";
export default {
  components: {
    Todo,
    Events,
    TotalCards,
    ProgressManager
  },
  data() {
    return {
      task_total: [],
      max: 100,
      value: [],
      proj_value: [],
      animate: true
    };
  },
  methods: {
    loadstatus() {
      axios.get("api/status").then(({ data }) => (this.value = data));
    }
  },

  mounted() {
    this.loadstatus();
    // Echo.channel("task-tracker").listen("TaskStatus", e => {
    //   console.log("Hello World");
    // });
  }
};
</script>
