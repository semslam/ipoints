module.exports = {
    apps: [
        {
            name: "master_worker",
            script: "/var/www/html/index.php",
            args: "cli masterWorker waitOnQueue",
            interpreter: "php",
            instances: process.env.NUMOFWORKERS || 1,
            error_file: '/var/www/html/application/logs/master-err.log',
            out_file: '/var/www/html/application/logs/master-out.log',
            time: true
        },
        {
            name: "slave_worker",
            script: "/var/www/html/index.php",
            args: "cli Utilities bulkTransferProcess",
            interpreter: "php",
            instances : 1,
            error_file: '/var/www/html/application/logs/slave-err.log',
            out_file: '/var/www/html/application/logs/slave-out.log',
            time: true
        }
    ]
 }