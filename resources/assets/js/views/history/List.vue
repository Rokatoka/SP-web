<template >
    <div class="col-10 offset-2">

        <filter-history ref="filter" v-on:filtered="filtered"></filter-history>
        <div class="col-8 offset-4">

            <table class="table mt-2">
                <thead class="thead-default">
                <tr>
                    <th> <button class="btn btn-success btn-sm" @click="$refs.makeApp.showModal()"><span class="fa fa-plus"></span></button></th>
                    <th>Doctor</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="appointment in appointments">
                    <td></td>
                    <td>{{appointment.doctor.user.first_name}} {{appointment.doctor.user.last_name}}</td>
                    <td v-if="appointment.status == 'approved'"><p class="text-primary">{{appointment.status}}</p></td>
                    <td v-if="appointment.status == 'canceled'"><p class="text-warning">{{appointment.status}}</p></td>
                    <td v-if="appointment.status == 'done'"><p class="text-success">{{appointment.status}}</p></td>
                    <td v-if="appointment.status == 'missed'"><p class="text-danger">{{appointment.status}}</p></td>
                    <td>{{appointment.date}} {{appointment.time}}</td>
                    <td>
                        <div class="pull-right">
                            <b-tooltip title="Open">
                                <router-link :to="{name:'history', params:{id: appointment.id}}" class="btn btn-outline-primary btn-sm"><span class="fa fa-address-card fa-2x"></span></router-link>
                            </b-tooltip>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <make-appointment ref="makeApp" :user="user.id" v-on:upd="getList"></make-appointment>
    </div>
</template>


<script>

    import { post, get } from '../../helpers/api'

    export default {

        data() {
            return {
                user: {
                },
                appointments:[],
                filterData:''
            }
        },
        components:{
            'filter-history': require('./Filter.vue'),
            'make-appointment': require('./modals/MakeAppointment.vue')
        },

        methods: {
            getData(){

                let _this = this;
                console.log(this.$root);
                get(
                    _this,
                    '/api/patient-info/' + this.$root.user.data.id,
                    {},
                    function(response){
                        _this.user = response.data;
                        _this.getList();
                    },
                    function(error){

                    }
                );
            },

            getList(){
                let _this = this;
                post(
                    _this,
                    '/api/appointment/get-list',
                    {
                        user: _this.user.id,
                        filter: _this.filterData
                    },
                    function (response) {
                        _this.appointments = response.data;
                        console.log(response.data);
                    },
                    function(error){

                    }
                );
            },

            filtered() {
                this.filterData = this.$refs.filter.filterData;
                console.log(this.filterData.search_text);

                this.$nextTick(function () {
                    this.getList();
                });

            },
        },
        created(){
            window.document.body.onscroll = this.getData();
        },

    }

</script>