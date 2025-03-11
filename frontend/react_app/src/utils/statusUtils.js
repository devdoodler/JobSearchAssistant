export const getStatus = (eventName) => {
   switch (eventName) {
      case 'job_application_submitted':
         return { status: 'Submitted', className: 'status-submitted' };
      case 'job_application_added':
         return { status: 'Added', className: 'status-added' };
      case 'job_application_rejected':
         return { status: 'Rejected', className: 'status-rejected' };
      default:
         return { status: 'Unknown Status', className: 'status-unknown' };
   }
};
