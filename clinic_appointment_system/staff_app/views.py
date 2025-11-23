from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.views import LoginView
from .forms import LoginForm, RegisterForm, DoctorForm
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.contrib.auth import logout as auth_logout
from .models import Doctor
from .models import Appointment, Doctor

class StaffLoginView(LoginView):
    template_name = 'staff_app/staff_login.html'
    authentication_form = LoginForm

    def form_valid(self, form):
        user = form.get_user()
        return super().form_valid(form)

    def get_success_url(self):
        return '/staff/dashboard/'

def staff_register(request):
    if request.method == 'POST':
        form = RegisterForm(request.POST)
        if form.is_valid():
            form.save()
            messages.success(request, "Account created successfully!")
            return redirect('staff_login')
        else:
            messages.error(request, "Please check the form for errors.")
    else:
        form = RegisterForm()

    return render(request, 'staff_app/staff_register.html', {'form': form})

@login_required 
def dashboard(request):
  all_appointments = Appointment.objects.all().order_by('-id')
  pending_appointments = Appointment.objects.filter(status='Pending')
  return render(request, 'staff_app/dashboard.html', {     'pending_appointments': pending_appointments,     'all_appointments': all_appointments, })

@login_required
def logout(request):
    auth_logout(request)
    return redirect('staff_login')

@login_required(login_url='staff_login')
def approve_appointment(request, id):
    appointment = get_object_or_404(Appointment, id=id)
    
    if request.method == 'POST':
        appointment.appointment_date = request.POST.get('appointment_date')
        appointment.appointment_time = request.POST.get('appointment_time')
        appointment.status = 'Approved'
        appointment.save()
        messages.success(request, "Appointment approved successfully!")
        return redirect('dashboard')

    return render(request, 'staff_app/approve_appointment.html', {
        'appointment': appointment
    })

@login_required(login_url='staff_login')
def reject_appointment(request, id):
    """
    Staff can reject an appointment request
    """
    appointment = get_object_or_404(Appointment, id=id)
    appointment.status = 'Rejected'
    appointment.save()
    messages.success(request, "Appointment rejected successfully!")
    return redirect('view_appointments')


@login_required(login_url='staff_login')
def create_appointment_request(request):
    if request.method == 'POST':
        client_id = request.POST.get('client_id')
        doctor_id = request.user.id  
        reason = request.POST.get('reason')
        Appointment.objects.create(
            client_id=client_id,
            doctor_id=doctor_id,
            reason=reason,
            status='Pending'
        )
        messages.success(request, "Appointment request created!")
        return redirect('dashboard')

    return render(request, 'staff_app/create_appointment.html')

@login_required(login_url='staff_login')
def staff_doctors(request):
    doctors = Doctor.objects.all()
    return render(request, 'staff_app/staff_doctors.html', {'doctors': doctors})

@login_required(login_url='staff_login')
def add_doctor(request):
    if request.method == 'POST':
        form = DoctorForm(request.POST)
        if form.is_valid():
            form.save()
            messages.success(request, "Doctor added successfully!")
            return redirect('staff_doctors')
    else:
        form = DoctorForm()
    return render(request, 'staff_app/staff_doctor_form.html', {'form': form, 'title': 'Add Doctor'})

@login_required(login_url='staff_login')
def edit_doctor(request, id):
    doctor = get_object_or_404(Doctor, id=id)
    if request.method == 'POST':
        form = DoctorForm(request.POST, instance=doctor)
        if form.is_valid():
            form.save()
            messages.success(request, "Doctor updated successfully!")
            return redirect('staff_doctors')
    else:
        form = DoctorForm(instance=doctor)
    return render(request, 'staff_app/staff_doctor_form.html', {'form': form, 'title': 'Edit Doctor'})

@login_required(login_url='staff_login')
def delete_doctor(request, id):
    doctor = get_object_or_404(Doctor, id=id)
    doctor.delete()
    messages.success(request, "Doctor deleted successfully!")
    return redirect('staff_doctors')
    
def client_login_php(request):
    return redirect('http://127.0.0.1:8001/index.php')
