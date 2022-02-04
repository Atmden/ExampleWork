<template>
    <v-container>
        <v-row justify="start">
            <v-col md="3" v-for="item in cards" :key="item.id">
                <v-card
                        class="mx-auto my-3 pa-2"
                        max-width="374"
                >
                    <template slot="progress">
                        <v-progress-linear
                                color="deep-purple"
                                height="10"
                                indeterminate
                        ></v-progress-linear>
                    </template>

                    <v-img
                            height="250"
                            :src="item.image"
                    ></v-img>

                    <v-card-title>{{ item.title }}</v-card-title>

                    <v-card-text>

                        <div class="my-4 text-subtitle-1">
                            {{ item.subtitle }}
                        </div>

                        <div>
                            {{ item.content }}
                        </div>
                    </v-card-text>

                    <v-divider class="mx-4"></v-divider>


                    <v-card-actions>
                        <v-btn
                                color="deep-purple lighten-2"
                                text
                                @click="editCard(item.id)"
                        >
                            Изменить
                        </v-btn>
                        <v-btn
                                color="deep-purple lighten-2"
                                text
                                @click="deleteDialog(item.id)"
                        >
                            Удалить
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

    </v-container>
</template>

<script>

    export default {
        name: 'CrudPage',
        props: ['cards'],

        methods: {
            editCard(id) {
                this.$emit('editCardEmit',{'id':id});
            },
            deleteDialog(id) {
                this.$emit('deleteCardEmit',{'id':id});
            },


        },
    }
</script>
