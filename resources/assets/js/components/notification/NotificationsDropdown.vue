<script>
  import $ from 'jquery';
  import Notification from './Notification.vue';
  import { NotificationProvider } from '../../providers/notificationProvider';
  export default {
    components: { Notification },
    data: () => ({
      total: 0,
      notifications: []
    }),
    mounted () {
      this.fetch()
      if (window.Echo) {
        this.listen()
      }
      this.initDropdown()
    },
    computed: {
      hasUnread () {
        return this.total > 0
      }
    },
    methods: {
      /**
       * Fetch notifications.
       *
       * @param {Number} limit
       */
      fetch (limit = 5) {
        NotificationProvider.get(limit)
                            .then(({ data: { total, notifications }}) => {
                              this.total = total
                              this.notifications = notifications.map(({ id, data, created }) => {
                              return {
                                id: id,
                                title: data.title,
                                body: data.body,
                                created: data.created,
                                action_url: data.action_url,
                                icon: data.icon
                              }
                            })
        })
      },

      fetchAll () {
        NotificationProvider.get()
                            .then(({ data: { total, notifications }}) => {
                              this.total = total
                              this.notifications = notifications.map(({ id, data, created }) => {
                              return {
                                id: id,
                                title: data.title,
                                body: data.body,
                                created: data.created,
                                action_url: data.action_url,
                                icon: data.icon
                              }
                            });
        })
      },

      /**
       * Mark the given notification as read.
       *
       * @param {Object} notification
       */
      markAsRead ({ id }) {
        const index = this.notifications.findIndex(n => n.id === id)
        if (index > -1) {
          this.total--
          this.notifications.splice(index, 1)
          NotificationProvider.delete(id);
        }
      },
      /**
       * Mark all notifications as read.
       */
      markAllRead () {
        this.total = 0
        this.notifications = []
        NotificationProvider.deleteAll();
      },
      /**
       * Listen for Echo push notifications.
       */
      listen () {
        window.Echo.private(`App.Models.User.${window.Laravel.user.id}`)
          .notification(notification => {
            console.log('Nova Notificação');
            this.total++;
            this.notifications.unshift(notification)
          })
          .listen('NotificationRead', ({ notificationId }) => {
            console.log('Notification Read Event');
            this.total--;
            const index = this.notifications.findIndex(n => n.id === notificationId)
            if (index > -1) {
              this.notifications.splice(index, 1)
            }
          })
          .listen('NotificationReadAll', () => {
            this.total = 0
            this.notifications = []
          })
      },
      /**
       * Initialize the notifications dropdown.
       */
      initDropdown () {
        const dropdown = $(this.$refs.dropdown)
        $(document).on('click', (e) => {
          if (!dropdown.is(e.target) && dropdown.has(e.target).length === 0 &&
            !$(e.target).parent().hasClass('notification-mark-read')) {
            dropdown.removeClass('open')
          }
        })
      },
      /**
       * Toggle the notifications dropdown.
       */
      toggleDropdown () {
        $(this.$refs.dropdown).toggleClass('open')
      }
    }
  }
</script>
<template>
  <li ref="dropdown" class="dropdown dropdown-notifications">
    <a @click.prevent="toggleDropdown" class="dropdown-toggle" href="#">
      <i :data-count="total" class="fa fa-bell notification-icon" :class="{ 'hide-count': !hasUnread }"></i>
    </a>

    <div class="dropdown-container">
      <div class="dropdown-toolbar">
        <div v-show="hasUnread" class="dropdown-toolbar-actions">
          <a href="#" @click.prevent="markAllRead">{{$t('notification.mark_all_read')}}</a>
        </div>

        <h3 class="dropdown-toolbar-title">{{$t('notification.notifications')}} ({{ total }})</h3>
      </div>

      <ul class="dropdown-menu">
        <notification v-for="notification in notifications"
          :key="notification.id"
          :notification="notification"
          v-on:read="markAsRead(notification)"
        ></notification>

        <li v-if="!hasUnread" class="notification">
          {{$t('notification.empty')}}.
        </li>
      </ul>

      <div v-if="hasUnread" class="dropdown-footer text-center">
        <a href="#" @click.prevent="fetchAll(null)">{{$t('notification.see_all')}}</a>
      </div>
    </div>
  </li>
</template>