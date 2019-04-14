<template>
        <div class="col-8 offset-3">

            <table class="table mt-2">
                <thead class="thead-default">
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="hospital in hospitals">
                    <td>
                        {{hospital.name}}
                    </td>
                    <td>
                        {{hospital.address}}
                    </td>
                    <td>
                        <div class="pull-right">
                            <b-tooltip title="Open">
                                <router-link :to="{name:'hospital', params:{id: hospital.id}}" class="btn btn-outline-primary btn-sm"><span class="fa fa-hospital-o fa-2x"></span></router-link>
                            </b-tooltip>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
</template>


<script>

    import { post, get } from '../../helpers/api'

    export default {

        data() {
            return {
                hospitals:[]
            }
        },
        methods: {
        },

        mounted() {
            let _this = this;
            get(
                _this,
                '/api/hospitals/get',
                {},
                function(response){
                    _this.hospitals = response.data;
                },
                function(error){

                }
            );
        }

    }

</script>