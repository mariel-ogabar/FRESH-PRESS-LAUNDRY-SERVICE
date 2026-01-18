<x-modal name="set-schedule" focusable>
    <div class="p-6">
        <h2 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Set Delivery Schedule: #<span x-text="selectedOrder.id"></span></h2>
        <div class="mt-4">
            <x-input-label value="Select Date & Time" />
            <x-text-input type="datetime-local" x-model="scheduledDate" class="mt-1 block w-full" />
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
            <x-primary-button @click="performUpdate('/orders/' + selectedOrder.id + '/schedule', { scheduled_date: scheduledDate }, () => window.location.reload())">
                Save Schedule
            </x-primary-button>
        </div>
    </div>
</x-modal>