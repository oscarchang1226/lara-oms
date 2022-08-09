<script setup>
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import { Head, Link, useForm} from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    orderApi: String
})

const form = useForm({
    vehicle: '',
    key: '',
    technician: ''
});

const orders = ref([]);

const submit = () => {
    let method = 'get';
    let url = props.orderApi;
    let params = {
        vehicle: vehicle.value,
        key: key.value,
        technician: technician.value
    };
    axios({method, url, params})
        .then(res => {
            orders.value = res.data.data;
        });

};

</script>

<template>
    <Head title="Orders" />

    <BreezeGuestLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Orders
                </h2>
                <div class="flex items-center justify-end">
                    <Link :href="route('order.new')" as="button" type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
                        New Order
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <BreezeLabel for="vehicle" value="Vehicle" />
                                <BreezeInput id="vehicle" type="text" class="mt-1 block w-full" v-model="form.vehicle" autofocus />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="key" value="Key" />
                                <BreezeInput id="key" type="text" class="mt-1 block w-full" v-model="form.key" autofocus />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="technician" value="Technician" />
                                <BreezeInput id="technician" type="text" class="mt-1 block w-full" v-model="form.technician" autofocus/>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Apply Filters
                                </BreezeButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
                <table class="bg-white shadow-sm sm:rounded-lg p-6 border-b border-gray-200 w-full">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Vehicle</th>
                            <th>Key</th>
                            <th>Technician</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(order, idx) in orders" :key="`order-${order.id}`">
                            <tr>
                                <td>{{order.id}}</td>
                                <td>{{order.vehicle}}</td>
                                <td>{{order.key}}</td>
                                <td>{{order.technician}}</td>
                                <td class="flex">
                                    <Link :href="route('order.edit', {order: order.id})" as="button" type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
                                        Edit
                                    </Link>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </BreezeGuestLayout>
</template>
