from django.urls import path
from . import views

urlpatterns = [
    path('register/', views.client_register, name='client_register'),
    path('login/', views.ClientLoginView.as_view(), name='client_login'),
    path('dashboard/', views.dashboard, name='client_dashboard'),
    #path('logout/', views.logout, name='logout'),
]
