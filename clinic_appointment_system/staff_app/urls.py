from django.urls import path
from . import views

urlpatterns = [
    path('register/', views.staff_register, name='staff_register'),
    path('login/', views.StaffLoginView.as_view(), name='staff_login'),
    path('dashboard/', views.dashboard, name='staff_dashboard'),
    path('logout/', views.logout, name='logout'),

    path('appointments/approve/<int:id>/', views.approve_appointment, name='approve_appointment'),
    path('appointments/reject/<int:id>/', views.reject_appointment, name='reject_appointment'),
    path('php/', views.client_login_php, name='client_login_php'),


    path('staff/doctors/', views.staff_doctors, name='staff_doctors'),
    path('staff/doctors/add/', views.add_doctor, name='add_doctor'),
    path('staff/doctors/edit/<int:id>/', views.edit_doctor, name='edit_doctor'),
    path('staff/doctors/delete/<int:id>/', views.delete_doctor, name='delete_doctor'),
]
