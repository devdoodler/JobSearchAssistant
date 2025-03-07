export const getStatus = (eventName) => {
   switch (eventName) {
      case 'job_application_submitted':
         return 'Submitted';
      case 'job_application_added':
         return 'Added';
      case 'job_application_rejected':
         return 'Rejected';
      default:
         return 'Unknown Status';
   }
};
