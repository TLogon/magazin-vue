<template>
    <div>
        <div class="spinner-fon"></div>
        <div class="spinner-border"></div>
    </div>
</template>

<script>
export default {
    name: "Spinner"
}
</script>

<style scoped>
.spinner-border {
    width: 3rem;
    height: 3rem;
    position: fixed;
    left: calc(50% - 1.5rem);
    top: calc(50% - 1.5rem);
}

.spinner-fon {
    position: fixed;
    width: 100%;
    height: 100%;
    background-color: black;
    opacity: 0.2;
}
</style>