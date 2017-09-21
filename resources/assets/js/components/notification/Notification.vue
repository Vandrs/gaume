<script>
  export default {
    props: ['notification'],
    methods: {
      markAsRead () {
        this.$emit('read')
      }
    }
  }
</script>
<template>
  <li class="notification">
    <div class="media">
      <div class="media-left">
        <div class="media-object" v-if="notification.icon">
          <img :src="notification.icon" alt="Foto Notificação">
        </div>
      </div>

      <div class="media-body">
        <strong class="notification-title">
          <a :href="notification.action_url">{{ notification.title }}</a>
        </strong>

        <p class="notification-desc">
          {{ notification.body }}
        </p>

        <div class="notification-meta">
          <small class="timestamp">
            <timeago :since="notification.created" :auto-update="30"></timeago>
          </small>
        </div>
        <p>
          <a href="#" @click.prevent="markAsRead" class="notification-mark-read" :title="$t('notification.mark_read')">
            {{$t('notification.mark_read')}}    
          </a>
        </p>
      </div>
    </div>
  </li>
</template>