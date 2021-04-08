<template>
    <div>
        <h1 class="font-bold text-3xl text-white mt-4 ml-8">Mis artistas &amp; canciones favoritos</h1>

        <div class="max-w-md rounded overflow-hidden shadow-lg mx-auto mt-8 border bg-grey-lightest" v-if="this.items.length == 0">
            <div class="py-4 px-4">
                <h2 class="font-bold text-xl pb-2 border-b">
                    Opciones
                </h2>
                <div class="mt-2">
                    <form v-on:submit.prevent="fetchItems">
                        <div class="text-l font-bold mb-2">Canción o artista</div>
                        <div class="inline-block relative w-auto mb-6">
                            <select class="block appearance-none w-full bg-white border border-grey-light hover:border-grey px-4 py-2 pr-8 rounded shadow"
                                v-model="type"
                            >
                                <option value="tracks">Top canciones</option>
                                <option value="artists">Top artistas</option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>

                        <div class="text-l font-bold mb-2">Tiempo aproximado</div>
                        <div class="inline-block relative w-auto mb-6">
                            <select class="block appearance-none w-full bg-white border border-grey-light hover:border-grey px-4 py-2 pr-8 rounded shadow"
                                v-model="term"
                            >
                                <option value="short_term">Últimas 4 semanas</option>
                                <option value="medium_term">Últimos 6 meses</option>
                                <option value="long_term">Desde el principio de los tiempos</option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>

                        <div class="block">
                            <button class="bg-neon-green hover:bg-neon-green-dark text-white font-bold py-2 px-4 rounded mt-2" id="fetchItems">Mostrar</button>
                        </div>
                       <!-- <p style="pt-4">
                            <small>
                                <span class="hint--right" data-hint="Measured on the expected preference a user has for a particular track or artist. It is based on user behavior, including play history."  style="display:inline-block;">calcular</span>
                            </small>
                        </p>-->
                    </form>
                </div>
            </div>
        </div>

        <reset-button v-show="items.length > 0" v-on:reset="reset"></reset-button>

        <favourite-tracks :tracks="items" v-if="items.length > 0 && type == 'tracks'"></favourite-tracks>
        <favourite-artists :artists="items" v-if="items.length > 0 && type == 'artists'"></favourite-artists>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                items: [],
                type: 'tracks',
                term: 'medium_term',
            }
        },

        methods: {
            fetchItems() {
                $('#fetchItems').addClass('loader');
                $('#fetchStats').prop("disabled", true);

                this.items = [];
                axios.get('/myfavouritesdata', {
                    params: {
                        type: this.type,
                        time_range: this.term
                    }
                }).then(response => {
                    this.items = response.data;
                    this.$nextTick( function() {
                        $('.bg-image').css('height', $('.bg-image').width());
                        $('#fetchItems').removeClass('loader');
                        $('#fetchItems').prop("disabled", false);
                    })
                })
            },

            reset() {
                this.items = []
            },
        }
    }
</script>
