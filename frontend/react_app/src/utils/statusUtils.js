export const getStatus = (eventName) => {
   switch (eventName) {
      case 'job_application_submitted':
         return { status: 'Submitted', className: 'status-submitted' };
      case 'job_application_added':
         return { status: 'Added', className: 'status-added' };
      case 'job_application_rejected':
         return { status: 'Rejected', className: 'status-rejected' };
      case 'job_interview_scheduled':
         return { status: 'Scheduled', className: 'status-scheduled' };
      case 'job_application_was_held':
         return { status: 'Was held', className: 'status-was-held' };
      default:
         return { status: 'Unknown Status', className: 'status-unknown' };
   }
};
