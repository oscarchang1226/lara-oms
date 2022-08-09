<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeSelect from '@/Components/Select.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import axios from 'axios';

const props = defineProps({
    apiUrl: String,
    method: String,
    vehicleOptions: Array,
    keyOptions: Array,
    technicianOptions: Array,
    formValues: Object
});

const form = useForm({
    vehicle: props.formValues.vehicle,
    key: props.formValues.key,
    technician: props.formValues.technician
});

const submit = () => {
    const options = {
        method: props.method,
        url: props.apiUrl,
        data: {
            vehicle_id: form.vehicle,
            key_id: form.key,
            technician_id: form.technician
        }
    }
    axios(options);
};

const deleteClick = () => {
    const options = {
        method: 'delete',
        url: props.apiUrl
    }
    axios(options);
};

</script>

<template>
    <BreezeGuestLayout>
        <Head title="Order Form" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order Form
            </h2>
        </template>

        <BreezeValidationErrors class="mb-4" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <BreezeLabel for="vehicle" value="Vehicle"/>
                                <BreezeSelect id="vehicle"  class="mt-1 block w-full" v-model="form.vehicle" required>
                                    <option v-for="vehicle in vehicleOptions" :value="vehicle.value" :key="`vehicle-${vehicle.value}`">
                                        {{vehicle.label}}
                                    </option>
                                </BreezeSelect>
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="key" value="Key"/>
                                <BreezeSelect id="key"  class="mt-1 block w-full" v-model="form.key" required>
                                    <option v-for="key in keyOptions" :value="key.value" :key="`key-${key.value}`">
                                        {{key.label}}
                                    </option>
                                </BreezeSelect>
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="technician" value="Technician"/>
                                <BreezeSelect id="technician"  class="mt-1 block w-full" v-model="form.technician" required>
                                    <option v-for="technician in technicianOptions" :value="technician.value" :key="`technician-${technician.value}`">
                                        {{technician.label}}
                                    </option>
                                </BreezeSelect>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    {{formValues.id ? 'Update' : 'Create'}}
                                </BreezeButton>
                                <BreezeButton class="ml-4" type="button" v-if="formValues.id" @click="deleteClick">
                                    Delete
                                </BreezeButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeGuestLayout>
</template>
