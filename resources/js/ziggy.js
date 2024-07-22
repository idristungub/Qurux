const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"ignition.healthCheck":{"uri":"_ignition\/health-check","methods":["GET","HEAD"]},"ignition.executeSolution":{"uri":"_ignition\/execute-solution","methods":["POST"]},"ignition.updateConfig":{"uri":"_ignition\/update-config","methods":["POST"]},"home":{"uri":"\/","methods":["GET","HEAD"]},"quran-quest.home":{"uri":"quran-quest","methods":["GET","HEAD"]},"achievements.create":{"uri":"achievements","methods":["GET","HEAD"]},"easyQuiz.skip":{"uri":"quiz\/easy\/{chapter}\/{verse}","methods":["GET","HEAD"],"parameters":["chapter","verse"]},"advanceQuiz.skip":{"uri":"quiz\/advance\/{chapter}","methods":["GET","HEAD"],"parameters":["chapter"]},"JuzEasyQuiz.skip":{"uri":"quiz\/easy\/{juz}\/{chapter}\/{verse}","methods":["GET","HEAD"],"parameters":["juz","chapter","verse"]},"juzAdvanceQuiz.skip":{"uri":"quiz\/advance\/{juz}\/{chapter}","methods":["GET","HEAD"],"parameters":["juz","chapter"]},"easyQuiz.check":{"uri":"quiz\/easy\/{chapter}\/{verse}","methods":["POST"],"parameters":["chapter","verse"]},"advanceQuiz.check":{"uri":"quiz\/advance\/{chapter}","methods":["POST"],"parameters":["chapter"]},"leaderboard.create":{"uri":"leaderboard","methods":["GET","HEAD"]},"profile.edit":{"uri":"profile","methods":["GET","HEAD"]},"profile.update":{"uri":"profile","methods":["PATCH"]},"profile.destroy":{"uri":"profile","methods":["DELETE"]},"register":{"uri":"register","methods":["GET","HEAD"]},"register.store":{"uri":"register","methods":["POST"]},"login":{"uri":"login","methods":["GET","HEAD"]},"login.store":{"uri":"login","methods":["POST"]},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"],"parameters":["token"]},"password.store":{"uri":"reset-password","methods":["POST"]},"verification.notice":{"uri":"verify-email","methods":["GET","HEAD"]},"verification.verify":{"uri":"verify-email\/{id}\/{hash}","methods":["GET","HEAD"],"parameters":["id","hash"]},"verification.send":{"uri":"email\/verification-notification","methods":["POST"]},"password.confirm":{"uri":"confirm-password","methods":["GET","HEAD"]},"password.update":{"uri":"password","methods":["PUT"]},"logout":{"uri":"logout","methods":["POST"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
