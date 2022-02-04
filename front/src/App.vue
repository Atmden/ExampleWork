<template>
    <v-app>
        <v-app-bar
                app
                color="primary"
                dark
        >
            <div class="d-flex align-center w100">
                <div class="d-flex align-center">
                    <v-img
                            alt="Vuetify Logo"
                            class="shrink mr-2"
                            contain
                            src="https://cdn.vuetifyjs.com/images/logos/vuetify-logo-dark.png"
                            transition="scale-transition"
                            width="40"
                    />
                    <span>CRUD TEST</span>
                </div>

                <div>
                    <v-btn

                            color="success"
                            @click="addCard"
                    >
                        <v-icon>mdi-plus</v-icon>
                        <span>Создать карточку</span>
                    </v-btn>
                </div>
            </div>


        </v-app-bar>

        <v-main>
            <CrudPage
            :cards="cards"
            @deleteCardEmit="deleteCardEmit"
            @editCardEmit="editCardEmit"
            ></CrudPage>
        </v-main>
        <v-dialog
                v-model="deleteConfirm"
                max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Удаление карточки
                </v-card-title>

                <v-card-text>
                    Подтвердите удаление
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                            color="green darken-1"
                            text
                            @click="deleteConfirm = false"
                    >
                        Отмена
                    </v-btn>

                    <v-btn
                            color="red darken-1"
                            text
                            @click="deleteCard"
                    >
                        Удалить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog
                v-model="addDialog"
                persistent
                max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Создание карточки</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                                    cols="12"
                                    md="12"
                            >
                                <v-file-input accept="image/*" v-model="image" outlined dense @change="onFileChange"
                                              required
                                              label="Изображение"/>
                                <v-img max-height="240" :src="imageUrl"/>
                            </v-col>
                            <v-col
                                    cols="12"
                                    md="4"
                            >
                                <v-text-field
                                        v-model="title"
                                        label="Заголовок*"
                                        required
                                ></v-text-field>
                            </v-col>
                            <v-col
                                    cols="12"
                                    md="8"

                            >
                                <v-text-field
                                        v-model="subtitle"
                                        label="Подзаголовок*"
                                        required
                                ></v-text-field>
                            </v-col>
                            <v-col
                                    cols="12"

                                    md="12"
                            >
                                <v-textarea
                                        v-model="content"
                                        outlined
                                        label="Описание*"
                                        required
                                ></v-textarea>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*обязательно к заполнению</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                            color="blue darken-1"
                            text
                            @click="closeAddDialog()"
                    >
                        Отмена
                    </v-btn>
                    <v-btn
                            color="green darken-1"
                            text
                            @click="addNewCard"
                    >
                        Сохранить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog
                v-model="editDialog"
                persistent
                max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Изменение карточки</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                                    cols="12"
                                    md="12"
                            >
                                <v-file-input accept="image/*" v-model="edit_image" outlined dense @change="onEditFileChange"
                                              required
                                              label="Изображение"/>
                                <v-img max-height="240" :src="imageUrl"/>
                            </v-col>
                            <v-col
                                    cols="12"
                                    md="4"
                            >
                                <v-text-field
                                        v-model="edit_Card.title"
                                        label="Заголовок*"
                                        required
                                ></v-text-field>
                            </v-col>
                            <v-col
                                    cols="12"
                                    md="8"

                            >
                                <v-text-field
                                        v-model="edit_Card.subtitle"
                                        label="Подзаголовок*"
                                        required
                                ></v-text-field>
                            </v-col>
                            <v-col
                                    cols="12"

                                    md="12"
                            >
                                <v-textarea
                                        v-model="edit_Card.content"
                                        outlined
                                        label="Описание*"
                                        required
                                ></v-textarea>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*обязательно к заполнению</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                            color="blue darken-1"
                            text
                            @click="closeEditDialog"
                    >
                        Отмена
                    </v-btn>
                    <v-btn
                            color="green darken-1"
                            text
                            @click="saveEditCard"
                    >
                        Сохранить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar
                v-model="snackbar"
                timeout="2000"
        >
            {{ messageSnack }}

            <template v-slot:action="{ attrs }">
                <v-btn
                        color="blue"
                        text
                        v-bind="attrs"
                        @click="snackbar = false"
                >
                    Закрыть
                </v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>
<style>
    .w100 {
        width: 100%;
        justify-content: space-between !important;
    }
</style>
<script>
    import axios from 'axios'
    import CrudPage from './components/CrudPage';

    export default {
        name: 'App',
        components: {
            CrudPage
        },
        data: () => ({
            cards: null,
            image: undefined,
            edit_image: undefined,
            imageUrl: "",
            addDialog: false,
            editDialog: false,
            snackbar: false,
            messageSnack: null,
            delete_id: null,
            edit_id: null,
            edit_Card: {
                image: null,
                title: null,
                subtitle: null,
                content: null,
            },
            deleteConfirm: false,
            title: null,
            subtitle: null,
            content: null,

        }),
        methods: {
            closeAddDialog() {
                this.addDialog = false;
            },
            closeEditDialog() {
                this.editDialog = false;
                this.imageUrl= null;
                this.edit_Card.title= null;
                this.edit_Card.subtitle= null;
                this.edit_Card.content= null;

            },
            addCard() {
                this.addDialog = true;
            },
            updateList() {
                axios.get('cards')
                    .then(response => {
                        if (response.data.success) {
                            this.cards = response.data.data;
                        }
                    });
            },
            editCardEmit(id)
            {

                this.edit_id = id.id;
                axios.get('cards/'+this.edit_id)
                    .then(response => {
                        if (response.data.success) {
                            this.edit_Card = response.data.data;
                            this.imageUrl = this.edit_Card.image;
                            console.log(this.edit_Card);
                        }
                    });
                this.editDialog = true;
            },
            deleteCardEmit(id)
            {
                this.delete_id = id.id;
                this.deleteConfirm = true;
            },
            deleteCard() {
                if (this.delete_id != null) {
                    axios.delete('cards/' + this.delete_id)
                        .then(response => {
                            if (response.data.success) {
                                this.updateList();
                            }
                            this.messageSnack = response.data.message;
                        })
                        .finally(() => {
                            this.delete_id = null;
                            this.deleteConfirm = false;
                            this.snackbar = true;
                        });
                }

            },
            saveEditCard(){
                let data = new FormData();

                data.append('image', this.edit_image);

                data.append('title', this.edit_Card.title);
                data.append('subtitle', this.edit_Card.subtitle);
                data.append('content', this.edit_Card.content);


                axios.post('cards/'+this.edit_id+'?_method=PUT', data)
                    .then(response => {
                        if (response.data.success) {
                            this.updateList();
                            this.editDialog = false;
                        }
                        this.messageSnack = response.data.message;
                    })
                    .finally(() => {
                        this.snackbar = true;
                    });
            },
            addNewCard() {

                let data = new FormData();
                data.append('image', this.image);
                data.append('title', this.title);
                data.append('subtitle', this.subtitle);
                data.append('content', this.content);
                axios.post('cards', data)
                    .then(response => {
                        if (response.data.success) {
                            this.updateList();
                        }
                        this.messageSnack = response.data.message;
                    })
                    .finally(() => {
                        this.image = undefined;
                        this.imageUrl = '';
                        this.title = null;
                        this.subtitle = null;
                        this.content = null;
                        this.addDialog = false;
                        this.snackbar = true;
                    });

            },
            createImage(file) {
                const reader = new FileReader();

                reader.onload = e => {
                    this.imageUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            onFileChange(file) {
                if (!file) {
                    this.imageUrl = '';
                    return;
                }
                this.createImage(file);
            },
            onEditFileChange(file) {
                if (!file) {
                    this.imageUrl = '';
                    return;
                }
                this.createImage(file);
            }
        },
        mounted() {
            axios.defaults.baseURL = process.env.VUE_APP_BASE_URL;
            this.updateList();
        },

    };
</script>
