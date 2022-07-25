<template>
<main style="display: flex; flex-wrap: nowrap; max-height: 100vh !important; height: 100vh !important; overflow-x: auto; overflow-y: hidden;">
            <admin-menu></admin-menu>
            <div class="d-flex flex-column flex-shrink-0 bg-light" style="width: calc(100% - 280px); overflow-y: scroll; overflow-x:hidden;">
                <router-view></router-view>
            </div>
        </main>
</template>