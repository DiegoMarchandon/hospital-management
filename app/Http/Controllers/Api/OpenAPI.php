<?php

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Hospital Management System API",
 *      description="RESTful API for Hospital Management System with role-based access control",
 *      @OA\Contact(
 *          email="support@hospital.com"
 *      ),
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     name="X-API-KEY",
 *     in="header",
 *     securityScheme="api_key",
 * )
 */

// ============================================================================
// DOCTORS ENDPOINTS
// ============================================================================

/**
 * @OA\Get(
 *      path="/api/doctors",
 *      operationId="getDoctors",
 *      tags={"Doctors"},
 *      summary="Get list of all doctors",
 *      description="Returns a paginated list of all doctors with their specialties",
 *      @OA\Parameter(
 *          name="page",
 *          description="Page number",
 *          required=false,
 *          in="query",
 *          @OA\Schema(type="integer", default=1)
 *      ),
 *      @OA\Parameter(
 *          name="limit",
 *          description="Results per page",
 *          required=false,
 *          in="query",
 *          @OA\Schema(type="integer", default=15)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="data", type="array", @OA\Items(
 *                  @OA\Property(property="id", type="integer"),
 *                  @OA\Property(property="name", type="string"),
 *                  @OA\Property(property="email", type="string"),
 *                  @OA\Property(property="phone", type="string"),
 *                  @OA\Property(property="specialty", type="object",
 *                      @OA\Property(property="id", type="integer"),
 *                      @OA\Property(property="name", type="string")
 *                  ),
 *                  @OA\Property(property="appointments_count", type="integer")
 *              )),
 *              @OA\Property(property="total", type="integer"),
 *              @OA\Property(property="page", type="integer"),
 *              @OA\Property(property="per_page", type="integer")
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized"
 *      )
 * )
 */

/**
 * @OA\Get(
 *      path="/api/doctors/{id}",
 *      operationId="getDoctorById",
 *      tags={"Doctors"},
 *      summary="Get doctor by ID",
 *      description="Returns doctor details with appointments and schedule",
 *      @OA\Parameter(
 *          name="id",
 *          description="Doctor ID",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="id", type="integer"),
 *              @OA\Property(property="name", type="string"),
 *              @OA\Property(property="email", type="string"),
 *              @OA\Property(property="specialty", type="object"),
 *              @OA\Property(property="appointments", type="array"),
 *              @OA\Property(property="schedule", type="array")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Doctor not found"
 *      )
 * )
 */

// ============================================================================
// PATIENTS ENDPOINTS
// ============================================================================

/**
 * @OA\Get(
 *      path="/api/patients",
 *      operationId="getPatients",
 *      tags={"Patients"},
 *      summary="Get list of all patients",
 *      description="Returns a paginated list of all patients",
 *      @OA\Parameter(
 *          name="page",
 *          description="Page number",
 *          required=false,
 *          in="query",
 *          @OA\Schema(type="integer", default=1)
 *      ),
 *      @OA\Parameter(
 *          name="limit",
 *          description="Results per page",
 *          required=false,
 *          in="query",
 *          @OA\Schema(type="integer", default=15)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="data", type="array", @OA\Items(
 *                  @OA\Property(property="id", type="integer"),
 *                  @OA\Property(property="name", type="string"),
 *                  @OA\Property(property="email", type="string"),
 *                  @OA\Property(property="phone", type="string"),
 *                  @OA\Property(property="id_number", type="string"),
 *                  @OA\Property(property="city", type="string"),
 *                  @OA\Property(property="appointments_count", type="integer")
 *              )),
 *              @OA\Property(property="total", type="integer"),
 *              @OA\Property(property="page", type="integer")
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized"
 *      )
 * )
 */

/**
 * @OA\Get(
 *      path="/api/patients/{id}",
 *      operationId="getPatientById",
 *      tags={"Patients"},
 *      summary="Get patient by ID",
 *      description="Returns patient details with appointments and medical records",
 *      @OA\Parameter(
 *          name="id",
 *          description="Patient ID",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="id", type="integer"),
 *              @OA\Property(property="name", type="string"),
 *              @OA\Property(property="email", type="string"),
 *              @OA\Property(property="appointments", type="array"),
 *              @OA\Property(property="medical_records", type="array")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Patient not found"
 *      )
 * )
 */

// ============================================================================
// APPOINTMENTS ENDPOINTS
// ============================================================================

/**
 * @OA\Get(
 *      path="/api/appointments",
 *      operationId="getAppointments",
 *      tags={"Appointments"},
 *      summary="Get list of appointments",
 *      description="Returns appointments (filtered by user role)",
 *      @OA\Parameter(
 *          name="page",
 *          description="Page number",
 *          required=false,
 *          in="query",
 *          @OA\Schema(type="integer", default=1)
 *      ),
 *      @OA\Parameter(
 *          name="status",
 *          description="Filter by status",
 *          required=false,
 *          in="query",
 *          @OA\Schema(type="string", enum={"pending", "confirmed", "completed", "cancelled"})
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="data", type="array", @OA\Items(
 *                  @OA\Property(property="id", type="integer"),
 *                  @OA\Property(property="doctor_id", type="integer"),
 *                  @OA\Property(property="patient_id", type="integer"),
 *                  @OA\Property(property="appointment_date", type="string", format="date"),
 *                  @OA\Property(property="appointment_time", type="string"),
 *                  @OA\Property(property="status", type="string"),
 *                  @OA\Property(property="reason", type="string"),
 *                  @OA\Property(property="doctor", type="object"),
 *                  @OA\Property(property="patient", type="object")
 *              )),
 *              @OA\Property(property="total", type="integer"),
 *              @OA\Property(property="page", type="integer")
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized"
 *      )
 * )
 */

/**
 * @OA\Post(
 *      path="/api/appointments",
 *      operationId="createAppointment",
 *      tags={"Appointments"},
 *      summary="Create a new appointment",
 *      description="Creates a new appointment (patients can only book for themselves)",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"doctor_id","appointment_date","appointment_time"},
 *              @OA\Property(property="doctor_id", type="integer", example=1),
 *              @OA\Property(property="appointment_date", type="string", format="date", example="2026-05-01"),
 *              @OA\Property(property="appointment_time", type="string", example="14:30"),
 *              @OA\Property(property="reason", type="string", example="Regular checkup")
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Appointment created successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="id", type="integer"),
 *              @OA\Property(property="doctor_id", type="integer"),
 *              @OA\Property(property="patient_id", type="integer"),
 *              @OA\Property(property="appointment_date", type="string", format="date"),
 *              @OA\Property(property="status", type="string", example="pending"),
 *              @OA\Property(property="created_at", type="string", format="date-time")
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error"
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized"
 *      )
 * )
 */

/**
 * @OA\Put(
 *      path="/api/appointments/{id}",
 *      operationId="updateAppointment",
 *      tags={"Appointments"},
 *      summary="Update appointment status",
 *      description="Update appointment status (doctors can confirm, patients can cancel)",
 *      @OA\Parameter(
 *          name="id",
 *          description="Appointment ID",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"status"},
 *              @OA\Property(property="status", type="string", enum={"confirmed", "completed", "cancelled"})
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Appointment updated successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="id", type="integer"),
 *              @OA\Property(property="status", type="string"),
 *              @OA\Property(property="updated_at", type="string", format="date-time")
 *          )
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden - Insufficient permissions"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Appointment not found"
 *      )
 * )
 */

/**
 * @OA\Delete(
 *      path="/api/appointments/{id}",
 *      operationId="deleteAppointment",
 *      tags={"Appointments"},
 *      summary="Cancel an appointment",
 *      description="Cancels an appointment (only patients can cancel their own)",
 *      @OA\Parameter(
 *          name="id",
 *          description="Appointment ID",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Appointment cancelled successfully"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden - Only your own appointments"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Appointment not found"
 *      )
 * )
 */
