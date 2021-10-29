<template>
  <div>
    <button
      type="button"
      class="btn m-0 p-1 shadow-none"
    >
      <i class="fas fa-heart mr-1" 
        :class="{'red-text':this.isLikedBy, 'animated heartBeat fast':this.gotTolike}"
        @click="clickLike"
      />
    </button>
    {{ countLikes }}
  </div>
</template>

<script>
export default {
  props: {
    initialLikedBy: {
      type: Boolean,
      default: false,
    },

    initialCountLikes: {
      type: Number,
      default: 0,
    },

    authorized: {
      type: Boolean,
      default: false,
    },
    endpoint: {
      type: String,
    },

  },
  
  data(){
    return {
      isLikedBy: this.initialLikedBy,
      countLikes: this.initialCountLikes,
      gotTolike: false,
    }
  },

  methods: {
    clickLike() {
      if (!this.authorized) {
        alert('いいね機能はログイン中のみ使用できます')
        return
      }

      this.isLikedBy
        ? this.unlike()
        : this.like()
    },

    async like() {
      const response = await axios.put(this.endpoint)

      this.isLikedBy = true
      this.countLikes = response.data.countLikes
      this.gotTolike = true
    },
    async unlike() {
      const response = await axios.delete(this.endpoint)

      this.isLikedBy = false
      this.countLikes = response.data.countLikes
      this.gotTolike = false
    },
    
  },
  
}
</script>