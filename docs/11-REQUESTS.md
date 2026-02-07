# Request Management

## Overview

The Request Management system provides a platform for employees to submit and track Leave Requests and Time Slip Requests, and for approvers to review and process them efficiently.

## Purpose

The Request Management system is designed to:
- Manage leave requests and time slip (attendance correction) requests
- Track request status and approval workflow
- Enable timely approval by designated approvers
- Maintain request records and audit trails
- Provide visibility into pending approvals

## What Users Can See

### For Employees

#### My Requests Dashboard
- **My Requests**: All your submitted leave and time slip requests
- **Pending Requests**: Requests awaiting approval
- **Request Status**: Current state of each request

#### Request Information
When viewing requests:
- **Request Type**: Leave Request or Time Slip Request
- **Submitted Date**: When request was created
- **Status**: Current state (Pending, Approved, Rejected)
- **Approver**: Person reviewing the request
- **Details**: Specific request information (dates, reason, etc.)
- **Approval/Rejection Reason**: Feedback from approver

#### Request Types
The system handles two types of requests:
- **Leave Requests**: Time off requests (covered in detail in [Leave Management](05-LEAVE.md))
- **Time Slip Requests**: Attendance correction requests (covered in detail in [Attendance](03-ATTENDANCE.md))

### For Approvers

#### Approver Request Dashboard
- **Requests for Approval**: Leave and time slip requests awaiting your approval
- **Leave Requests Tab**: Leave requests you need to review
- **Time Slip Requests Tab**: Attendance correction requests you need to review
- **Request Filters**: Sort and search options

#### Approver Features
- Review leave and time slip requests
- Approve or reject requests
- Add comments and feedback
- Track request processing

## How to Use Request Management

### For Employees: Viewing Your Requests

#### Accessing Your Requests

1. **Login** to your dashboard
2. Click **"Requests"** from main menu
3. You'll see two tabs:
   - **Leave Requests**: Your submitted leave requests
   - **Time Slip Requests**: Your attendance correction requests

#### Viewing Leave Requests

Leave requests are detailed in the [Leave Management](05-LEAVE.md) guide. Quick summary:
1. Navigate to **"My Requests"** or **"Leave"** section
2. See all your leave requests with status
3. View details, approval status, and approver comments

#### Viewing Time Slip Requests

Time slip requests are for correcting attendance records. See [Attendance](03-ATTENDANCE.md) for details:
1. Navigate to **"My Requests"** → **"Time Slip Requests"** tab
2. See submitted time slip corrections
3. View request status:
   - **Pending**: Awaiting approval
   - **Approved**: Correction accepted
   - **Rejected**: Correction declined

### For Approvers: Managing Requests

#### Accessing Requests for Approval

1. **Login** to your account
2. Click **"Requests"** from main menu
3. See **"Requests for Approval"** page with two tabs:
   - **Leave Requests**: Leave requests awaiting your decision
   - **Time Slip Requests**: Attendance corrections awaiting your decision

#### Reviewing Leave Requests

1. **Leave Requests Tab**
   - See list of pending leave requests
   - Each request shows:
     - Employee name and ID
     - Leave type
     - Start and end dates
     - Number of days
     - Reason for leave
     - Current leave balance

2. **Take Action**:
   
   **To Approve:**
   - Click **"Approve"** button next to the request
   - Confirm approval
   - Employee is notified
   - Leave balance updated
   - Attendance calendar updated

   **To Reject:**
   - Click **"Reject"** button
   - **Provide rejection reason** (required)
   - Confirm rejection
   - Employee is notified with reason
   - Leave balance remains unchanged

#### Reviewing Time Slip Requests

1. **Time Slip Requests Tab**
   - See list of pending attendance corrections
   - Each request shows:
     - Employee name and ID
     - Date of attendance
     - Requested start time
     - Requested end time
     - Reason for correction

2. **Take Action**:
   
   **To Approve:**
   - Click **"Approve"** button
   - Confirm approval
   - Attendance record is updated
   - Employee is notified

   **To Reject:**
   - Click **"Reject"** button
   - Provide rejection reason
   - Confirm rejection
   - Attendance record remains unchanged
   - Employee is notified

#### Filtering Requests

Use filters to find specific requests:
- **Search**: By employee name or ID
- **Leave Type**: Filter by leave category (for leave requests)
- **Date**: Filter by request date or leave dates
- **Status**: View pending, approved, or rejected requests

## Request Features

### Leave Request Workflow

Standard leave request lifecycle:
1. **Submit**: Employee creates leave request
2. **Review**: Approver evaluates request
3. **Approve/Reject**: Decision made by approver
4. **Update**: System updates leave balance and calendar
5. **Notify**: Employee receives decision notification

Details in [Leave Management](05-LEAVE.md)

### Time Slip Request Workflow

Standard time slip request lifecycle:
1. **Submit**: Employee requests attendance correction
2. **Review**: Approver evaluates correction request
3. **Approve/Reject**: Decision made by approver
4. **Update**: Attendance record corrected if approved
5. **Notify**: Employee receives decision notification

Details in [Attendance](03-ATTENDANCE.md)

### Multi-level Approval

For certain requests (based on settings):
- **Level 1**: Direct supervisor approval
- **Level 2**: Department head approval (if configured)
- Each level can approve or reject

### Request Notifications

**Email Notifications for:**
- **Submitter**:
  - Request submission confirmation
  - Approval/rejection notification
  - Status updates
- **Approver**:
  - New request submitted
  - Pending approval reminder

**Dashboard Notifications:**
- Real-time alerts for new requests
- Pending action indicators

### Request History and Audit Trail

Complete request tracking:
- All status changes logged
- Approver decisions recorded
- Timestamps for all activities
- Complete audit trail maintained

## Best Practices

### For Employees

- **Submit early**: Request leave well in advance
- **Be specific**: Provide clear reasons for time slip corrections
- **Check status**: Monitor request progress regularly
- **Respond promptly**: If approver asks questions
- **Professional communication**: Maintain courteous tone

### For Approvers

- **Timely review**: Process requests quickly (within 24-48 hours)
- **Fair evaluation**: Apply consistent criteria
- **Clear communication**: Explain rejections thoroughly
- **Verify information**: Check leave balances and attendance records
- **Document decisions**: Keep detailed records of approval reasons

## Troubleshooting

### Common Issues

**Request Not Showing**
- Check correct tab (Leave vs Time Slip)
- Verify filters aren't hiding the request
- Refresh the page
- Contact administrator if missing

**Cannot Submit Request**
- For leave: See [Leave Management](05-LEAVE.md)
- For time slip: See [Attendance](03-ATTENDANCE.md)

**Approver Not Responding**
- Contact approver directly
- Check if approver is on leave
- Escalate to supervisor if urgent
- Contact HR for assistance

**Request Rejected Without Clear Reason**
- Contact approver for clarification
- Review organizational policies
- Resubmit with improvements if applicable

## Tips for Effective Request Management

### For Employees
- **Plan ahead**: Submit requests early
- **Provide context**: Clear reasons help approval
- **Track patterns**: Note common approval/rejection reasons
- **Communicate**: Keep approvers informed of urgent needs
- **Stay organized**: Keep records of all requests

### For Approvers
- **Regular review**: Check requests daily
- **Be consistent**: Apply policies fairly
- **Provide feedback**: Help employees understand decisions
- **Monitor workload**: Ensure team coverage during leaves
- **Track trends**: Identify patterns in requests

## Related Documentation

For detailed information on specific request types:
- **Leave Requests**: See [Leave Management](05-LEAVE.md)
- **Time Slip Requests**: See [Attendance](03-ATTENDANCE.md)
- **Request Approvers**: See [Admin Dashboard](02-ADMIN-DASHBOARD.md) for setting up approvers

---

**Previous**: [Forms](10-FORMS.md) | [Back to Index](00-INDEX.md)
