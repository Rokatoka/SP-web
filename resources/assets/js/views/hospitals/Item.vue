<template>

    <div>

        <div class="col-2 offset-2 fixed-top h-100 pt-4">
            <router-link :to="{name:'hospital-departments', params:{id: hospital}}" class="btn btn-primary btn-block text-left btn-sm"><span class="fa fa-building-o"></span> Watch Departments</router-link>
        </div>

        <div class="col-8 offset-4 pr-5">
            <div class="mb-5 mt-1">
                <span class="h4"></span>
            </div>

            <div class="h5">Hospital Information</div>
            <table class="table table-responsive">
                <tbody>
                <tr v-if="hospital==3">
                    <td width="40%"><img src="/img/3.jpg" :style="{width:'100%' , height: '40%' }"/></td>
                    <td>Rehabilitation center for 306 beds.
                        The center provides rehabilitation assistance to children with neuropsychiatric pathology with the help of complex correction of motor, psycho-emotional, speech disorders, leading to social maladjustment of children with neuroendocrine and psycho-neurological disadaptations.</td>
                </tr>
                <tr v-if="hospital==2">
                    <td width="40%"><img src="/img/2.jpg" :style="{width:'100%' , height: '40%' }"/></td>
                    <td>Diagnostic center with a capacity of 500 visits per shift.
                        The center provides a wide range of consultative and diagnostic assistance based on high technologies, including using nuclear medicine methods.</td>
                </tr>
                <tr v-if="hospital==1">
                    <td width="40%"><img src="/img/1.jpg" :style="{width:'100%' , height: '40%' }"/></td>
                    <td>Multidisciplinary hospital with 450 beds.
                        The Center provides specialized and highly specialized assistance in the field of pediatrics, pediatric surgery, neonatology, obstetrics-gynecology, in vitro fertilization and transplantation.</td>
                </tr>
                <tr v-if="hospital==4">
                    <td width="40%"><img src="/img/4.jpg" :style="{width:'100%' , height: '120%' }"/></td>
                    <td>Multidisciplinary hospital with 450 beds.
                        The Center provides specialized and highly specialized assistance in the field of pediatrics, pediatric surgery, neonatology, obstetrics-gynecology, in vitro fertilization and transplantation.</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script>

    export default{
        props:['id'],

        data(){
            return{
                hospital:this.$route.params.id
            }
        }
    }

</script>