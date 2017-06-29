<script>
	import { LessonProvider } from '../../providers/lessonProvider';
	import { AppErrorBag } from '../../components/app/AppErrorBag';
	import { LessonStatus } from '../../components/lesson/LessonStatus';
	import { Pagination } from 'uiv';
	export default {
		components: { Pagination },
		data () {
			return {
				lessons: [],
				user: window.Laravel.user,
				filters: {
					teacher: null,
					student: null,
					status: null
				},
				roles: {
					admin: "ADMIN",
					teacher: "TEACHER",
					student: "STUDENT"
				},
				viewLessonLink: window.Laravel.baseUrl + '/app/aula/',
				status: LessonStatus,
				pagination: {
					currentPage: 1,
					totalPages: 1,	
					linksRange: 5
				}
			};
		},
		mounted() {
			this.getLessons();
		},
		methods: {
			getLessons: function (params)	{
				LessonProvider.list(params)
							  .then((response) => {
							  	this.lessons = response.data.data;
							  	this.pagination.currentPage = response.data.meta.pagination.current_page;
							  	this.pagination.totalPages = response.data.meta.pagination.total_pages;
							  })
							  .catch((error) => {
							  		var errors = AppErrorBag.parseErrors(
									  				error.response.status,
									  				error.response.data
									  			);
								  	window.app.$emit('app:show-alert', errors, "danger"); 	
							  });
			},
			getLessonDuration: function (lesson) {
				var totalHours = 0;
				var acceptedStatus = [this.status.IN_PROGRESS, this.status.FINISHED];
				for (var period of lesson.periods) {
					if (acceptedStatus.indexOf(period.status) >= 0) {
						totalHours += period.hours;
					}
				}
				return totalHours;
			},
			paginate: function (page) {
				var filters = this.getFilters();
				filters.page = page;
				this.getLessons(filters);
			},
			seePage: function (page) {
				window.location.href = this.viewLessonLink+page;
			},
			search: function () {
				this.getLessons(this.getFilters());
			},
			getFilters: function () {
				return this.filters;
			}
		}
	}
</script>
<template>
<div>
	<div class="row">
		<div v-if="user.role != roles.teacher" class="col-xs-12 col-md-3 control-group">
			<label>{{$t('app.teacher')}}</label>
			<input  type="text" v-model="filters.teacher" name="teacher" class="form-control">
		</div>
		<div v-if="user.role != roles.student" class="col-xs-12 col-md-3 control-group">
			<label>{{$t('app.student')}}</label>
			<input type="text" v-model="filters.student"  name="student" class="form-control">
		</div>
		<div class="col-xs-12 col-md-3 control-group">
			<label>{{$t('lesson.labels.status')}}</label>
			<select class="form-control" name="status" v-model="filters.status">
				<option value=""></option>
				<option v-for="id in status" v-bind:value="id">{{$t('lesson.status.'+id)}}</option>
			</select>
		</div>
		<div class="col-xs-12 col-md-3">
			<label class="block-label">&nbsp;</label>
			<button v-on:click="search" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> {{$t('app.search')}}</button>
		</div>
	</div>
	<div class="row margin-top-20">
		<div class="col-xs-12">
			<table class="table table-bordered table-stripped table-blue-header">
				<thead>
					<tr>
						<th v-if="user.role != roles.student">{{$t('app.student')}}</th>
						<th v-if="user.role != roles.teacher">{{$t('app.teacher')}}</th>
						<th>{{$t('lesson.table.status')}}</th>
						<th>{{$t('lesson.labels.duration')}}</th>
						<th>{{$t('app.actions')}}</th>
					</tr>
				</thead>
				<tbody v-if="lessons.length > 0">
					<tr v-for="lesson in lessons">
						<td v-if="user.role != roles.student">{{lesson.student.name}}</td>
						<td v-if="user.role != roles.teacher">{{lesson.teacher.name}}</td>
						<td>{{$t('lesson.status.'+lesson.status)}}</td>
						<td>{{getLessonDuration(lesson)+" "+$tc('app.hour',getLessonDuration(lesson))}}</td>
						<td>
							<a class="btn btn-default" v-bind:title="$t('app.view')" v-on:click="seePage(lesson.id)"><i class="glyphicon glyphicon-eye-open"></i></a>
						</td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr>
						<td colspan="5">{{$t('app.noRegisterFound')}}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-xs-12 text-center">
			<pagination v-model="pagination.currentPage" v-on:change="paginate" :total-page="pagination.totalPages" :max-size="pagination.linksRange" :boundary-links="true"></pagination>
		</div>
	</div>		
</div>
</template>