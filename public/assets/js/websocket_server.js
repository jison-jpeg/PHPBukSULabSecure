import { Server as WebSocketServer } from 'ws';
import HID from 'node-hid';

const wss = new WebSocketServer({ port: 8080 });

// ACR122 vendor and product IDs
const vendorId = 0x072f;
const productId = 0x2200;

const device = new HID.HID(vendorId, productId);

wss.on('connection', function connection(ws) {
  device.on('data', function(data) {
    // Parse data to extract RFID number
    const rfidNumber = parseRFIDData(data);
    
    // Send RFID number to connected clients
    ws.send(JSON.stringify({ rfidNumber }));
  });
});

function parseRFIDData(data) {
    // Implement your parsing logic here based on the format of RFID data
    // For example, you might need to convert bytes to hexadecimal or ASCII
    // This will depend on the specifications of your RFID card
    // Return the parsed RFID number
}
