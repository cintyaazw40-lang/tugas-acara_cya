<?php
class Mahasiswa
{
    private ?int $id;
    private string $nim;
    private string $nama;
    private string $email;
    private int $prodiId;
    private int $angkatan;
    private string $status;

    public function __construct(
        string $nim,
        string $nama,
        string $email,
        int $prodiId,
        int $angkatan,
        string $status = 'aktif',
        ?int $id = null
    ) {
        $this->setNim($nim);
        $this->setNama($nama);
        $this->setEmail($email);
        $this->setProdiId($prodiId);
        $this->setAngkatan($angkatan);
        $this->setStatus($status);
        $this->id = $id;
    }

    // ================= GETTER =================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getProdiId(): int
    {
        return $this->prodiId;
    }

    public function getAngkatan(): int
    {
        return $this->angkatan;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    // ================= SETTER (dengan validasi) =================

    public function setNim(string $nim): void
    {
        // Validasi: NIM harus berupa angka
        if (!ctype_digit($nim)) {
            throw new InvalidArgumentException("NIM harus berupa angka, diberikan: '{$nim}'");
        }
        $this->nim = $nim;
    }

    public function setNama(string $nama): void
    {
        // Validasi: nama tidak boleh kosong
        if (trim($nama) === '') {
            throw new InvalidArgumentException("Nama mahasiswa tidak boleh kosong");
        }
        $this->nama = trim($nama);
    }

    public function setEmail(string $email): void
    {
        $this->email = trim($email);
    }

    public function setProdiId(int $prodiId): void
    {
        if ($prodiId <= 0) {
            throw new InvalidArgumentException("Prodi harus dipilih");
        }
        $this->prodiId = $prodiId;
    }

    public function setAngkatan(int $angkatan): void
    {
        $tahunSekarang = (int) date('Y');
        if ($angkatan < 2000 || $angkatan > $tahunSekarang) {
            throw new InvalidArgumentException("Angkatan tidak valid: {$angkatan}");
        }
        $this->angkatan = $angkatan;
    }

    public function setStatus(string $status): void
    {
        $validStatus = ['aktif', 'cuti', 'lulus'];
        if (!in_array($status, $validStatus, true)) {
            throw new InvalidArgumentException("Status harus salah satu dari: " . implode(', ', $validStatus));
        }
        $this->status = $status;
    }

    // ================= HELPER =================

    // Membuat object Mahasiswa dari baris hasil query database
    public static function fromArray(array $data): self
    {
        return new self(
            $data['nim'],
            $data['nama'],
            $data['email'],
            (int) $data['prodi_id'],
            (int) $data['angkatan'],
            $data['status'],
            isset($data['id']) ? (int) $data['id'] : null
        );
    }
}
