<template>
    <div class="has-many-detail">
        <table class="table w-full">
            <thead>
                <tr>
                    <th class="text-left">Title</th>
                    <th class="text-left">Description</th>
                    <th class="text-left">Price</th>
                    <th class="text-left">Gst</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item of field.value">
                    <td>{{ item.title }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.price }}</td>
                    <td>{{ item.gst }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
