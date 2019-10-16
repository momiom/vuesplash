<template>
  
  <div v-if="hasPost" class="photo-list">
    <div class="grid">
      <Photo class="grid__item" v-for="photo in photos" :key="photo.id" :item="photo" />
    </div>
    <Pagination :current-page="currentPage" :last-page="lastPage" />
  </div>

  <div v-else>
    <p style="text-align: center; color: #8a8a8a;">
    Nothing post.
    </p>
  </div>
</template>

<script>
import { OK } from "../util";
import Photo from "../components/Photo.vue";
import Pagination from "../components/Pagination.vue";

export default {
  components: {
    Photo,
    Pagination
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  },
  data() {
    return {
      photos: [],
      currentPage: 0,
      lastPage: 0,
      hasPost: false,
    };
  },
  methods: {
    async fetchPhotos() {
      console.log("fetchPhotos: %s", `/api/photos/?page=${this.page}`);
      const response = await axios.get(`/api/photos/?page=${this.page}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.photos = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;
      this.hasPost = response.data.total != 0;
      console.log(JSON.stringify(response.data, '', 4));
    }
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchPhotos();
      },
      immediate: true
    }
  }
};
</script>