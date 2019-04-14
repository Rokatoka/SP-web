<template>
    <div class="col-8 offset-3">

        <table class="table mt-2">
            <thead class="thead-default">
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="doctor in doctors">
                <td>
                    {{doctor.user.first_name}} {{doctor.user.patronymic}}
                </td>
                <td>
                    {{doctor.position.name}}
                </td>
                <td>
                    <div class="pull-right">
                        <b-tooltip title="Open">
                            <router-link :to="{name:'user', params:{id: doctor.user.id}}" class="btn btn-outline-primary btn-sm"><span class="fa fa-user-md fa-2x"></span></router-link>
                        </b-tooltip>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>


<script>

    import { post, get } from '../../../helpers/api'

    export default {
        props:['id'],

        data() {
            return {
                doctors:[],
                department:this.$route.params.id
            }
        },
        methods: {
        },

        mounted() {
            let _this = this;
            post(
                _this,
                '/api/hospitals/departments/doctors/get',
                {
                    department:_this.department
                },
                function(response){
                    _this.doctors = response.data;
                },
                function(error){

                }
            );
        }

    }

</script>