from django.shortcuts import render, redirect
from django.contrib.auth.views import LoginView
from .forms import LoginForm, RegisterForm
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.views.decorators.cache import never_cache
from django.contrib.auth import logout as auth_logout

class ClientLoginView(LoginView):
    template_name = 'client_app/client_login.html'
    authentication_form = LoginForm

    def form_valid(self, form):
        user = form.get_user()

        if user.is_staff:
            messages.error(self.request, "Staff must log in through the staff portal.")
            return self.form_invalid(form)

        return super().form_valid(form)

    def get_success_url(self):
        return '/client/dashboard/'


def client_register(request):
    if request.method == 'POST':
        form = RegisterForm(request.POST)
        if form.is_valid():
            form.save()
            messages.success(request, "Account created successfully!")
            return redirect('client_login')
        else:
            messages.error(request, "Please check the form for errors.")
    else:
        form = RegisterForm()

    return render(request, 'client_app/client_register.html', {'form': form, 'messages':messages})

@never_cache
@login_required(login_url='client_login')
def dashboard(request):
    if request.user.is_authenticated:
      return render(request, 'client_app/dashboard.html')
    else:
      return redirect('login')
"""
@login_required
def logout(request):
    auth_logout(request)
    return redirect('client_login')
"""    