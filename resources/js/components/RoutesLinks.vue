<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <router-link :to="{name: 'home'}">Home</router-link>
                <router-link :to="{name: 'postIndex'}">Blog</router-link>
                <router-link :to="{name: 'about'}">About</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'RoutesLinks',
}
</script>

<style>

</style>

