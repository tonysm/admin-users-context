<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <groups-list class="mb-5"></groups-list>

                <users-list></users-list>
            </div>
        </div>
    </div>
</template>

<script>
    import GroupsList from './GroupsList.vue';
    import UsersList from './UsersList.vue';

    export default {
        components: {
            UsersList,
            GroupsList,
        },
    }
</script>
