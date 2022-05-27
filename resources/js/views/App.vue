<template>
    <div>
        <container-posts />
        <router-view></router-view>
    </div>
</template>

<script>
import ContainerPosts from  '../components/ContainerPosts.vue';
export default {
    name: 'App',
    components: {
        ContainerPosts,
    }
}
</script>

<style>

</style>
