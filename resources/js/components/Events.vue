<template>
  <div class="card">
    <full-calendar :events="events" :config="config"></full-calendar>

    <button @click="newModal" class="btn btn-success">
      Add New Event
      <i class="fas fa-calendar-week"></i>
    </button>

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
            <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add New Event</h5>
            <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update Event</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent=" editmode ? updateEvent() : createEvent()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.event_title"
                  type="text"
                  name="event_title"
                  placeholder="Enter Event's Title"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('event_title')}"
                >
                <has-error :form="form" field="event_title"></has-error>
              </div>

              <div class="form-group">
                <input
                  v-model="form.event_description"
                  type="text"
                  name="event_description"
                  placeholder="Enter Event's Description"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('event_description')}"
                >
                <has-error :form="form" field="start_date"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.start_date"
                  type="date"
                  id="start_date"
                  name="start_date"
                  placeholder="Enter Start Date"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('start_date')}"
                >
                <has-error :form="form" field="start_date"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.end_date"
                  id="end_date"
                  type="date"
                  name="end_date"
                  placeholder="Enter End Date"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('end_date')}"
                >
                <has-error :form="form" field="end_date"></has-error>
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
import { FullCalendar } from "vue-full-calendar";
import "fullcalendar/dist/fullcalendar.css";
export default {
  components: {
    FullCalendar
  },

  name: "Events",
  data() {
    return {
      config: {
        defaultView: "month"
      },

      events: [
        {
          title: "Submit Project",
          start: "2019-03-29",
          end: "2019-03-31",
          allDay: true
        },

        {
          title: "Project Meeting",
          start: "2019-04-03",
          end: "2019-04-05"
        },
        {
          title: "Group Lunch",
          start: "2019-04-15",
          end: "2019-04-15"
        },
        {
          title: "Deadline",
          start: "2019-04-20",
          color: "Red",
          textColor: "Black"
        }
      ],

      editmode: false,
      form: new Form({
        id: "",
        user_id: "",
        event_title: "",
        event_description: "",
        start_date: "",
        end_date: "",
        task: ""
      })
    };
  },
  methods: {
    // loadEvents() {
    //   axios.get("api/event").then(({ data }) => (this.events = data));
    // },

    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    createEvent() {
      this.$Progress.start();
      this.form.post("api/event");
      Fire.$emit("Successful");
      $("#addNew").modal("hide");

      toast.fire({
        type: "success",
        title: "Event created successfully"
      });
      this.$Progress.finish();
    },

    updateEvent() {
      this.$Progress.start();
      this.form
        .put("api/event/" + this.form.id)
        .then(() => {
          $("#addNew").modal("hide");
          toast.fire({
            type: "success",
            title: "Event updated successfully"
          });
          this.$Progress.finish();
          Fire.$emit("Successful");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    }
  },
  created() {
    //this.loadEvents();
    Fire.$on("Successful", () => {
      this.loadEvents();
    });
  }
};
</script>
