from django.db import models

class Client(models.Model):
    id = models.AutoField(primary_key=True)
    username = models.CharField(max_length=50, unique=True)
    password = models.CharField(max_length=255)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        managed = False
        db_table = 'clients'

    def __str__(self):
        return self.username


class Doctor(models.Model):
    id = models.BigAutoField(primary_key=True)
    name = models.CharField(max_length=255)
    specialization = models.CharField(max_length=255)

    class Meta:
        managed = False
        db_table = 'doctors'

    def __str__(self):
        return self.name


class Appointment(models.Model):
    id = models.AutoField(primary_key=True)
    client = models.ForeignKey(
        Client,
        on_delete=models.SET_NULL,
        null=True,
        db_column='client_id'
    )
    doctor = models.ForeignKey(
        Doctor,
        on_delete=models.SET_NULL,
        null=True,
        db_column='doctor_id'
    )
    reason = models.TextField(null=True, blank=True)
    status = models.CharField(max_length=20, default='Pending')
    appointment_date = models.DateField(null=True, blank=True)
    appointment_time = models.TimeField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        managed = False
        db_table = 'appointments'

    def __str__(self):
        client_name = self.client.username if self.client else "No Client"
        doctor_name = self.doctor.name if self.doctor else "No Doctor"
        return f"{client_name} -> {doctor_name} ({self.status})"
