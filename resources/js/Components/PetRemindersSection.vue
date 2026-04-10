<script setup>
import { ref, onMounted, computed } from 'vue';
import RemindersModal from '@/Components/RemindersModal.vue';

const props = defineProps({
  pet: {
    type: Object,
    required: true,
  },
});

const showReminderModal = ref(false);
const reminders = ref([]);
const vaccineNames = ref([]);
const medicationNames = ref([]);
const clinicNames = ref([]);
const loading = ref(false);
const filter = ref('all'); 

const filteredReminders = computed(() => {
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  return reminders.value.filter(reminder => {
    const reminderDate = new Date(reminder.date);
    reminderDate.setHours(0, 0, 0, 0);

    if (filter.value === 'overdue') {
      return reminderDate < today && !reminder.notification_sent;
    }
    if (filter.value === 'today') {
      return reminderDate.getTime() === today.getTime();
    }
    if (filter.value === 'upcoming') {
      return reminderDate > today;
    }
    return true;
  });
});

const stats = computed(() => ({
  total: reminders.value.length,
  overdue: reminders.value.filter(r => {
    const d = new Date(r.date);
    d.setHours(0, 0, 0, 0);
    return d < new Date(new Date().setHours(0, 0, 0, 0)) && !r.notification_sent;
  }).length,
  today: reminders.value.filter(r => {
    const d = new Date(r.date);
    const t = new Date();
    d.setHours(0, 0, 0, 0);
    t.setHours(0, 0, 0, 0);
    return d.getTime() === t.getTime();
  }).length,
}));

onMounted(() => {
  fetchReminders();
  fetchReminderNames();
});

function fetchReminders() {
  loading.value = true;
  fetch(`/pets/${props.pet.id}/reminders`)
    .then(r => r.json())
    .then(data => {
      reminders.value = data.reminders || [];
      vaccineNames.value = data.vaccineNames || [];
      medicationNames.value = data.medicationNames || [];
      clinicNames.value = data.clinicNames || [];
    })
    .catch(err => console.error('Error fetching reminders:', err))
    .finally(() => loading.value = false);
}

function fetchReminderNames() {
  // Fetch vaccine names
  fetch(`/api/pets/${props.pet.id}/reminders/vaccines/names`)
    .then(r => r.json())
    .then(data => vaccineNames.value = data.data)
    .catch(() => {});

  // Fetch medication names
  fetch(`/api/pets/${props.pet.id}/reminders/medications/names`)
    .then(r => r.json())
    .then(data => medicationNames.value = data.data)
    .catch(() => {});

  // Fetch clinic names
  fetch(`/api/pets/${props.pet.id}/reminders/clinics/names`)
    .then(r => r.json())
    .then(data => clinicNames.value = data.data)
    .catch(() => {});
}

function handleReminderModalClose() {
  showReminderModal.value = false;
  fetchReminders();
  fetchReminderNames();
}

function getReminderIcon(type) {
  const icons = {
    vaktsiin: '💉',
    ravim: '💊',
    arstivisiit: '🏥',
  };
  return icons[type] || '🔔';
}

function getReminderClasses(type) {
  const base = 'p-4 rounded-xl border-l-4 flex items-start justify-between';
  
  if (type === 'vaktsiin') {
    return `${base} border-blue-500 bg-blue-50`;
  }
  if (type === 'ravim') {
    return `${base} border-yellow-500 bg-yellow-50`;
  }
  if (type === 'arstivisiit') {
    return `${base} border-green-500 bg-green-50`;
  }
  
  return `${base} border-gray-300 bg-gray-50`;
}

function markAsNotified(reminder) {
  if (!confirm('Märgi see meeldetuletus teavitatuks?')) return;

  fetch(`/pets/${props.pet.id}/reminders/${reminder.id}/notify`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json',
    },
  })
    .then(() => fetchReminders())
    .catch(err => console.error('Error marking reminder:', err));
}

function deleteReminder(reminder) {
  if (!confirm('Oled kindel, et soovid kustutada selle meeldetuletuse?')) return;

  fetch(`/pets/${props.pet.id}/reminders/${reminder.id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json',
    },
  })
    .then(() => fetchReminders())
    .catch(err => console.error('Error deleting reminder:', err));
}

function getTypeLabel(type) {
  const labels = {
    vaktsiin: 'Vaktsiin',
    ravim: 'Ravim',
    arstivisiit: 'Arstivisiit',
  };
  return labels[type] || type;
}
</script>

<template>
  <div class="reminders-section bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
      <div>
        <h2 class="text-xl font-semibold text-gray-900">Meeldetuletused</h2>
        <p class="text-sm text-gray-500 mt-1">Kokku {{ stats.total }} | Täna {{ stats.today }} | Ületähtaeg {{ stats.overdue }}</p>
      </div>
      <button
        @click="showReminderModal = true"
        class="flex items-center gap-2 px-4 py-2 bg-[#2D5A3D] text-white rounded-lg hover:bg-[#234830] transition font-medium text-sm"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Lisa meeldetuletus
      </button>
    </div>

    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-100 flex gap-2 flex-wrap">
      <button
        v-for="option in ['all', 'today', 'upcoming', 'overdue']"
        :key="option"
        @click="filter = option"
        :class="[
          'px-3 py-1.5 rounded-lg text-sm font-medium transition',
          filter === option
            ? 'bg-[#2D5A3D] text-white'
            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
        ]"
      >
        <span v-if="option === 'all'">Kõik</span>
        <span v-else-if="option === 'today'">Täna</span>
        <span v-else-if="option === 'upcoming'">Tulevased</span>
        <span v-else-if="option === 'overdue'">Ületähtaeg</span>
      </button>
    </div>

    <!-- Content -->
    <div class="px-6 py-6">
      <!-- Loading state -->
      <div v-if="loading" class="flex items-center justify-center py-12">
        <div class="animate-spin">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#2D5A3D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
        </div>
      </div>

      <!-- Empty state -->
      <div v-else-if="filteredReminders.length === 0" class="text-center py-12">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <p class="text-gray-500 font-medium">Meeldetuletusi pole</p>
        <p class="text-gray-400 text-sm mt-1">Lisa esimene meeldetuletus nuppu vajutades</p>
      </div>

      <!-- Reminders list -->
      <div v-else class="space-y-3">
        <div
          v-for="reminder in filteredReminders"
          :key="reminder.id"
          :class="getReminderClasses(reminder.type)"
        >
          <!-- Left content -->
          <div class="flex items-start gap-3 flex-1">
            <span class="text-2xl">{{ getReminderIcon(reminder.type) }}</span>
            <div class="flex-1">
              <div class="flex items-baseline gap-2 mb-1">
                <p class="font-semibold text-sm text-gray-900">{{ reminder.name }}</p>
                <span class="text-xs px-2 py-0.5 bg-white bg-opacity-60 rounded text-gray-600">
                  {{ getTypeLabel(reminder.type) }}
                </span>
              </div>
              <p class="text-xs text-gray-600">
                {{ reminder.date }} · {{ reminder.time }}
              </p>
              <p v-if="reminder.notification_sent" class="text-xs text-green-600 font-medium mt-1">
                ✓ Teavitatud
              </p>
            </div>
          </div>

          <!-- Right actions -->
          <div class="flex gap-2 ml-4">
            <button
              v-if="!reminder.notification_sent"
              @click="markAsNotified(reminder)"
              class="px-2 py-1 text-xs font-medium bg-white bg-opacity-70 text-gray-700 rounded hover:bg-opacity-100 transition"
              title="Märgi teavitatuks"
            >
              Märgi
            </button>
            <button
              @click="deleteReminder(reminder)"
              class="px-2 py-1 text-xs font-medium bg-white bg-opacity-70 text-red-600 rounded hover:bg-opacity-100 transition"
              title="Kustuta meeldetuletus"
            >
              Kustuta
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <RemindersModal
      v-if="showReminderModal"
      :petId="pet.id"
      :petName="pet.name"
      :vaccineNames="vaccineNames"
      :medicationNames="medicationNames"
      :clinicNames="clinicNames"
      @close="handleReminderModalClose"
    />
  </div>
</template>

<style scoped>
/* Smooth transitions */
button {
  transition: all 0.2s ease;
}

/* Loading animation */
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>