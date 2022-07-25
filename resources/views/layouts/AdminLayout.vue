<template>
<div id="app">
        <main>
            <admin-menu></admin-menu>
            <div class="d-flex flex-column flex-shrink-0 bg-light" style="width: calc(100% - 280px); overflow-y: scroll; overflow-x:hidden;">
                <router-view></router-view>
            </div>
        </main>
    </div>
    <style scoped>
        main {
            display: flex;
            flex-wrap: nowrap;
            height: 100vh !important;
            height: -webkit-fill-available;
            max-height: 100vh !important;
            overflow-x: auto;
            overflow-y: hidden;
        }
    </style>
</template>