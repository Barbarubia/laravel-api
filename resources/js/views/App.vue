<template>
    <div>
        <routes-links />
        <router-view></router-view>
    </div>
</template>

<script>
import RoutesLinks from  '../components/RoutesLinks.vue';
export default {
    name: 'App',
    components: {
        RoutesLinks,
    }
}
</script>

<style>

</style>
