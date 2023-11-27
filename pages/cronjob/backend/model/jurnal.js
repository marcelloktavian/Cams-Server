const {db1,db2} = require("../config/dbs")
class Jurnal {
    async getData() {
        try {
            const jurnal = await db1.query("SELECT ifnull(SUM(CASE WHEN c.cron_status = 'DONE' THEN 1 ELSE 0 END),0) AS done,ifnull(SUM(CASE WHEN c.cron_status = 'FAIL' THEN 1 ELSE 0 END),0) AS fail,ifnull(SUM(CASE WHEN c.cron_status = 'PENDING' THEN 1 ELSE 0 END),0) AS pending,ifnull(SUM(CASE WHEN c.deleted = 0 AND c.id IS NOT NULL THEN 1 ELSE 0 END),0) AS total_process,'jurnal' AS name FROM cron_jurnal c WHERE c.deleted = 0;")
            return jurnal[0]
        } catch (error) {
            throw error
        }
    }
    async insertCron() {
        
            const crons = await db1.query("SELECT * FROM cron_jurnal WHERE deleted = 0 AND cron_status = 'PENDING' ORDER BY lastmodified ASC")
            const ref = await db1.query("SELECT CONCAT(DATE_FORMAT(CURRENT_DATE,'%y%m%d'),IF(STR_TO_DATE(SUBSTR(no_jurnal,1,6),'%y%m%d') < CURRENT_DATE,'00000',SUBSTR(no_jurnal,6,5) + 1)) as num, id FROM jurnal ORDER BY id DESC LIMIT 1") 
            if (crons[0]) {
                for (let i = 0; i < crons[0].length; i++) {
                    let cj = crons[0][i]
                    let parent_id = ref[0][0].id + i + 1
                    
                    try {
                        await db1.query("START TRANSACTION"); // Mulai transaksi untuk tabel jurnal
    
                        await db1.query("insert into jurnal(id,no_jurnal,tgl,keterangan,total_debet,total_kredit,deleted,user,lastmodified,status,state_edit) values (?,?,?,?,?,?,?,?,?,?,?)", [
                            parent_id, i === 1 ? ref[0][0].num : parseInt(ref[0][0].num) + i + 1, cj.tgl, cj.keterangan, cj.total_debet, cj.total_kredit, 0, cj.user, 'NOW()', cj.status, cj.state_edit
                        ]);

                        await db1.query("UPDATE cron_jurnal set cron_status = 'DONE' where id = ?",[cj.id])

                        await db1.query("COMMIT"); // Commit transaksi untuk tabel jurnal
                            
                        const detail = await db1.query("SELECT * FROM cron_jurnal_detail WHERE cron_status = 'PENDING' AND deleted = 0 AND id_parent = ?", [cj.id]);
                        
                        if (detail[0]) {
                            for (let j = 0; j < detail[0].length; j++) {
                                await db1.query("START TRANSACTION"); // Mulai transaksi untuk tabel jurnal_detail
                                const det = detail[0][j];
                                try {
                                    await db1.query("INSERT INTO jurnal_detail(id_parent,id_akun,no_akun,nama_akun,status,debet,kredit,keterangan,deleted,user,lastmodified) values(?,?,?,?,?,?,?,?,?,?,?)", [
                                        parent_id, det.id_akun, det.no_akun, det.nama_akun, det.status, det.debet, det.kredit, det.keterangan, det.deleted, det.user, det.lastmodified
                                    ]); // insert ke tabel detail
                                    await db1.query("UPDATE cron_jurnal_detail set cron_status = 'DONE' where id = ?",[det.id]) // update status cron
                                    await db1.query("COMMIT"); // Commit transaksi untuk tabel jurnal_detail
                                } catch (error) {
                                    console.log(error);
                                    await db1.query("ROLLBACK"); // Rollback transaksi untuk tabel jurnal_detail
                                    await db1.query("UPDATE cron_jurnal_detail set cron_status = 'FAIL' where id = ?",[det.id]) // update status cron
                                }
                            }
                        }
                    } catch (error) {
                        console.log(error);
                        await db1.query("ROLLBACK")
                        await db1.query("UPDATE cron_jurnal set cron_status = 'FAIL' where id = ?",[cj.id])
                    }
                    
                }
            }

    }
}

module.exports = Jurnal