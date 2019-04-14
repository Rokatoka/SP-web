<template>
    <div>
        <user-filter v-if="$common.data.roles" ref="filter" :load="load" v-on:filtered="filtered"></user-filter>
        <!-- Результаты -->
        <div class="col-8 offset-4">
            Found <b>{{ total }}</b> users
            <button type="button" class="btn btn-primary btn-sm ml-2" @click="$refs.newUser.showModal()">Add</button>

            <table class="table mt-4">
                <thead class="thead-default">
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users">
                    <td>{{ user.first_name + " " + user.last_name }}</td>
                    <td>{{ user.role.name }}</td>
                    <td>
                        <div class="pull-right">
                            <b-tooltip title="Open">
                                <router-link :to="{name:'user', params:{id: user.id}}" class="btn btn-outline-primary btn-sm"><span class="fa fa-user"></span></router-link>
                            </b-tooltip>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <user-form ref="newUser" :data="$common.data" :_form="newUser" v-on:formSending="filtered"></user-form>

    </div>
</template>


<script>

    import { get } from '../../helpers/api'

    export default {

        data() {
            return {
                load: false,
                scrollLoad: false,
                newUser: '',
                users: [],
                total: 0,
                resource_url: '/api/users',
                next_url: '',
                default_url: '/api/users',
                filterData:''
            }
        },
        components: {
            'user-form': require('./Form.vue'),
            'user-filter': require('./Filter.vue'),
        },
        methods: {
            getList() {


                this.resource_url = this.scrollLoad ? this.next_url : this.resource_url;

                if (!this.resource_url){
                    this.scrollLoad = false;
                    return false;
                }

                this.load = true;

                let _this = this;


                get(_this, this.resource_url, {params: this.filterData}, function (response) {

                    let json = response.data;

                    _this.next_url = json.next_page_url;

                    _this.users = _this.users.concat(json.data);
                    console.log(response.data);

                    _this.total = json.total;

                    _this.scrollLoad = false;
                    _this.load = false;

                }, function () {

                    _this.scrollLoad = false;
                    _this.load = false;

                });

            },
            filtered() {
                this.resource_url = this.default_url;
                this.users = [];
                this.total = 0;
                this.filterData = this.$refs.filter.filterData;
                console.log(this.filterData.search_text);

                this.$nextTick(function () {
                    this.$router.push({ path: '/control/users', query: this.filterData });
                    this.getList();
                });

            },
            handleScroll(e){
                let body = document.body,
                    html = document.documentElement;

                let height = Math.max( body.scrollHeight, html.scrollHeight);

                if($(window).scrollTop() + $(window).height() > $(document).height() - 100 && !this.scrollLoad) {
                    this.scrollLoad = true;
                    this.$nextTick(function () {
                        this.getList();
                    })
                }

            }

        },

        created() {

            window.document.body.onscroll = this.handleScroll;
        }

    }
</script>