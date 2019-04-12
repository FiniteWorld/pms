<template>
  <div class="contacts-list">
    <ul>
      <li
        v-for="contact in sortedContacts"
        :key="contact.id"
        @click="selectContact(contact)"
        :class="{'selected': contact == selected }"
      >
        <div class="contact">
          <dt>{{contact.name}}</dt>
          <dl>{{contact.email}}</dl>
        </div>
        <span class="unread" v-if="contact.unread">{{contact.unread}}</span>
      </li>
    </ul>
  </div>
</template>


<script>
export default {
  props: {
    contacts: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      selected: this.contacts.length ? this.contacts[0] : null
    };
  },
  methods: {
    selectContact(contact) {
      this.selected = contact;
      this.$emit("selected", contact);
    }
  },
  computed: {
    sortedContacts() {
      return _.sortBy(this.contacts, [
        contact => {
          if (contact == this.selected) {
            return Infinity;
          }
          return contact.unread;
        }
      ]).reverse();
    }
  }
};
</script>
<style lang="scss" scoped>
.contacts-list {
  flex: 2;
  max-height: 400px;
  overflow: scroll;
  border-left: 0.5px solid #a6a6a6;
  ul {
    list-style-type: none;
    padding-left: 0;
  }
  li {
    display: flex;
    padding: 1.5px;
    border-bottom: 0.5px solid #aaaaaa;
    height: 70px;
    position: relative;
    cursor: pointer;

    &.selected {
      background: #dfdfdf;
    }

    span.unread {
      background: #82e0a8;
      color: #fff;
      position: absolute;
      right: 11px;
      top: 20px;
      display: flex;
      font-weight: 700;
      min-width: 20px;
      justify-content: center;
      align-items: center;
      line-height: 20px;
      font-size: 12px;
      padding: 0 4px;
      border-radius: 3px;
    }

    .contact {
      flex: 3;
      font-size: 12px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: center;

      p {
        margin: 0;

        &.name {
          font-weight: bold;
        }
      }
    }
  }
}
</style>