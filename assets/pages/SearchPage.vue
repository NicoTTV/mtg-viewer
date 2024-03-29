<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { fetchAllCards, searchCards, filterBySetCode } from '../services/cardService';

const cards = ref([]);
let loadingCards = ref(false);
const search = ref('');
let lastSearch = ref('');
let controller = new AbortController();
let setCode = ref('');
const $route = useRoute();

function validateQuery() {
    if (search.value.length >= 3) {
        loadingCards.value = true;
        lastSearch = searchCards(search.value.toLowerCase(), controller.signal).then((data) => {
            cards.value = data;
            loadingCards.value = false;
        });

    }
}

function onSearchChange(event) {
    search.value = event.target.value;
    if (lastSearch.value !== '') {
        controller.abort();
        controller = new AbortController();
    }
    validateQuery();
}

async function loadSetCodeCards() {
    loadingCards.value = true;
    await filterBySetCode('/card'+$route.path, [["query",search.value.toLowerCase()],["setCode",setCode.value]]).then((data) => {
        cards.value = data;
        loadingCards.value = false;
    });
}

</script>

<template>
    <div>
        <h1>Rechercher une Carte</h1>
    </div>
    <div class="search-form">
        <input type="text" @input="onSearchChange" placeholder="Rechercher une carte..." />
        <button @click="validateQuery">Recercher</button>
    </div>
    <div class="card-list">
        <div v-if="cards.length === 0 && !loadingCards">Aucune carte trouvée</div>
        <div v-else-if="loadingCards">Loading...</div>
        <div v-else>
          <div>
            <p>Filtre</p>
            <input type="text" v-model="setCode" placeholder="Filtrer par code de set..." />
            <button @click="loadSetCodeCards">Filtrer</button>
          </div>
            <div class="card" v-for="card in cards" :key="card.id">
                <router-link :to="{ name: 'get-card', params: { uuid: card.uuid } }"> {{ card.name }} - {{ card.uuid }} </router-link>
            </div>
        </div>
    </div>
</template>
